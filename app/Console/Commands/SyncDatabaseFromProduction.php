<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SyncDatabaseFromProduction extends Command
{
    /**
     * Copy the production database into the local (sandbox) database.
     */
    protected $signature = 'db:sync-from-production {--skip-confirmation : Executa sem pedir confirmacao}';

    protected $description = 'Copia a base de dados de producao para a base de dados local/sandbox';

    private string $mysqlBin;
    private string $mysqldumpBin;

    public function handle(): int
    {
        $this->mysqlBin = $this->resolveBinary('MYSQL_BIN', 'mysql');
        $this->mysqldumpBin = $this->resolveBinary('MYSQLDUMP_BIN', 'mysqldump');

        $remote = $this->getMysqlConfig('production');
        $local = $this->getMysqlConfig('sandbox');

        $this->line('Base de dados remota (producao):');
        $this->line("  Host: {$remote['host']}:{$remote['port']}");
        $this->line("  DB:   {$remote['database']}");
        $this->line('');
        $this->line('Base de dados local (sandbox):');
        $this->line("  Host: {$local['host']}:{$local['port']}");
        $this->line("  DB:   {$local['database']}");

        if (! $this->option('skip-confirmation')) {
            if (! $this->confirm('Isto vai apagar e recriar a base de dados local. Quer continuar?')) {
                $this->info('Operacao cancelada.');
                return self::SUCCESS;
            }
        }

        $dumpPath = $this->makeDumpPath($remote['database']);

        try {
            $this->recreateLocalDatabase($local);
            $this->exportRemoteDatabase($remote, $dumpPath);
            $this->importIntoLocal($local, $dumpPath);
        } catch (ProcessFailedException $e) {
            $this->error('Falhou: ' . $e->getMessage());
            return self::FAILURE;
        } finally {
            if (is_file($dumpPath)) {
                @unlink($dumpPath);
            }
        }

        $this->info('Copia concluida com sucesso.');
        return self::SUCCESS;
    }

    private function recreateLocalDatabase(array $local): void
    {
        $sql = sprintf(
            'DROP DATABASE IF EXISTS `%s`; CREATE DATABASE `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;',
            $local['database'],
            $local['database']
        );

        $command = sprintf('%s -e %s', $this->buildMysqlCli($local, $this->mysqlBin), escapeshellarg($sql));
        $this->runShellCommand($command, 'A recriar base de dados local...');
    }

    private function exportRemoteDatabase(array $remote, string $dumpPath): void
    {
        $command = sprintf(
            '%s %s > %s',
            $this->buildMysqlCli($remote, $this->mysqldumpBin),
            escapeshellarg($remote['database']),
            escapeshellarg($dumpPath)
        );

        $this->runShellCommand($command, 'A exportar base de dados remota...');
    }

    private function importIntoLocal(array $local, string $dumpPath): void
    {
        $command = sprintf(
            '%s %s < %s',
            $this->buildMysqlCli($local, $this->mysqlBin),
            escapeshellarg($local['database']),
            escapeshellarg($dumpPath)
        );

        $this->runShellCommand($command, 'A importar para a base de dados local...');
    }

    private function runShellCommand(string $command, string $label): void
    {
        $this->info($label);

        $process = Process::fromShellCommandline($command, null, null, null, null);
        $process->run(function ($type, $buffer) {
            $this->output->write($buffer);
        });

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    private function buildMysqlCli(array $config, string $binary): string
    {
        $parts = [
            escapeshellarg($binary),
            '-h ' . escapeshellarg($config['host']),
            '-P ' . escapeshellarg((string) $config['port']),
            '-u ' . escapeshellarg($config['username']),
        ];

        if ($config['password'] !== '') {
            $parts[] = '--password=' . escapeshellarg($config['password']);
        }

        if (str_contains($binary, 'mysqldump')) {
            $parts[] = '--single-transaction';
            $parts[] = '--quick';
            $parts[] = '--routines';
            $parts[] = '--events';
            $parts[] = '--triggers';
        }

        return implode(' ', $parts);
    }

    private function getMysqlConfig(string $profile): array
    {
        $isProduction = $profile === 'production';

        return [
            'host' => env($isProduction ? 'DB_HOST_PRODUCTION' : 'DB_HOST_SANDBOX', env('DB_HOST', '127.0.0.1')),
            'port' => env($isProduction ? 'DB_PORT_PRODUCTION' : 'DB_PORT_SANDBOX', env('DB_PORT', '3306')),
            'database' => env($isProduction ? 'DB_DATABASE_PRODUCTION' : 'DB_DATABASE_SANDBOX', env('DB_DATABASE', 'forge')),
            'username' => env($isProduction ? 'DB_USERNAME_PRODUCTION' : 'DB_USERNAME_SANDBOX', env('DB_USERNAME', 'forge')),
            'password' => env($isProduction ? 'DB_PASSWORD_PRODUCTION' : 'DB_PASSWORD_SANDBOX', env('DB_PASSWORD', '')),
        ];
    }

    private function makeDumpPath(string $database): string
    {
        $temp = tempnam(sys_get_temp_dir(), 'dbdump_');

        if ($temp === false) {
            throw new \RuntimeException('Nao foi possivel criar ficheiro temporario para o dump.');
        }

        $path = $temp . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $database) . '.sql';
        rename($temp, $path);

        return $path;
    }

    private function resolveBinary(string $envKey, string $default): string
    {
        return env($envKey, $default);
    }
}
