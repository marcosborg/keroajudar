<?php

declare(strict_types=1);

if (! hash_equals('f6f24f63ddba0a5fa06a6281239e391b954509fc', (string) ($_GET['token'] ?? ''))) {
    http_response_code(404);
    exit('Not Found');
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$migrationStatus = $kernel->call('migrate', ['--force' => true]);
$migrationOutput = $kernel->output();
$clearStatus = $kernel->call('optimize:clear');
$clearOutput = $kernel->output();

@unlink(__FILE__);

header('Content-Type: application/json');
echo json_encode([
    'ok' => $migrationStatus === 0 && $clearStatus === 0,
    'migration_status' => $migrationStatus,
    'migration_output' => $migrationOutput,
    'clear_status' => $clearStatus,
    'clear_output' => $clearOutput,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
