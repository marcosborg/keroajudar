<?php

namespace App\Services;

use App\Models\Beneficiary;
use Illuminate\Support\Str;

class BeneficiaryBannerService
{
    public const FORMATS = [
        'medium-rectangle' => ['label' => 'Retângulo médio', 'width' => 300, 'height' => 250],
        'leaderboard' => ['label' => 'Leaderboard', 'width' => 728, 'height' => 90],
        'mobile-banner' => ['label' => 'Banner mobile', 'width' => 320, 'height' => 100],
        'wide-skyscraper' => ['label' => 'Skyscraper largo', 'width' => 160, 'height' => 600],
    ];

    public function formats(): array
    {
        return self::FORMATS;
    }

    public function render(Beneficiary $beneficiary, string $format): string
    {
        abort_unless(isset(self::FORMATS[$format]), 404);

        $size = self::FORMATS[$format];
        $width = $size['width'];
        $height = $size['height'];
        $name = $this->xml($beneficiary->name);
        $initials = $this->xml($this->initials($beneficiary->name));

        if ($format === 'leaderboard') {
            $wideName = $this->xml(Str::limit($beneficiary->name, 43, '…'));
            $content = <<<SVG
<circle cx="48" cy="45" r="31" fill="#ffffff" fill-opacity=".96"/><text x="48" y="52" text-anchor="middle" font-size="20" font-weight="800" fill="#198754">{$initials}</text>
<text x="96" y="28" font-size="13" font-weight="700" fill="#d9fbe8">APOIE ESTA ASSOCIAÇÃO</text><text x="96" y="55" font-size="20" font-weight="800" fill="#ffffff">{$wideName}</text>
<rect x="566" y="25" width="142" height="40" rx="20" fill="#ffffff"/><text x="637" y="51" text-anchor="middle" font-size="14" font-weight="800" fill="#146c43">CONHECER E APOIAR</text>
SVG;
        } elseif ($format === 'mobile-banner') {
            $shortName = $this->xml(Str::limit($beneficiary->name, 25, '…'));
            $content = <<<SVG
<circle cx="44" cy="50" r="32" fill="#ffffff" fill-opacity=".96"/><text x="44" y="57" text-anchor="middle" font-size="20" font-weight="800" fill="#198754">{$initials}</text>
<text x="86" y="32" font-size="11" font-weight="700" fill="#d9fbe8">APOIE ESTA ASSOCIAÇÃO</text><text x="86" y="53" font-size="16" font-weight="800" fill="#ffffff">{$shortName}</text>
<rect x="86" y="65" width="138" height="25" rx="13" fill="#ffffff"/><text x="155" y="82" text-anchor="middle" font-size="11" font-weight="800" fill="#146c43">CONHECER E APOIAR</text>
SVG;
        } elseif ($format === 'wide-skyscraper') {
            $lines = $this->nameLines($beneficiary->name, 16, 4);
            $nameSvg = $this->lineText($lines, 80, 178, 24, 16);
            $content = <<<SVG
<circle cx="80" cy="82" r="48" fill="#ffffff" fill-opacity=".96"/><text x="80" y="92" text-anchor="middle" font-size="28" font-weight="800" fill="#198754">{$initials}</text>
<text x="80" y="142" text-anchor="middle" font-size="11" font-weight="700" fill="#d9fbe8">APOIE ESTA</text><text x="80" y="157" text-anchor="middle" font-size="11" font-weight="700" fill="#d9fbe8">ASSOCIAÇÃO</text>
{$nameSvg}
<rect x="14" y="472" width="132" height="52" rx="26" fill="#ffffff"/><text x="80" y="494" text-anchor="middle" font-size="11" font-weight="800" fill="#146c43">CONHECER</text><text x="80" y="510" text-anchor="middle" font-size="11" font-weight="800" fill="#146c43">E APOIAR</text>
SVG;
        } else {
            $lines = $this->nameLines($beneficiary->name, 28, 2);
            $nameSvg = $this->lineText($lines, 150, 132, 24, 19);
            $content = <<<SVG
<circle cx="150" cy="64" r="43" fill="#ffffff" fill-opacity=".96"/><text x="150" y="73" text-anchor="middle" font-size="26" font-weight="800" fill="#198754">{$initials}</text>
<text x="150" y="112" text-anchor="middle" font-size="12" font-weight="700" fill="#d9fbe8">APOIE ESTA ASSOCIAÇÃO</text>
{$nameSvg}
<rect x="68" y="190" width="164" height="40" rx="20" fill="#ffffff"/><text x="150" y="216" text-anchor="middle" font-size="13" font-weight="800" fill="#146c43">CONHECER E APOIAR</text>
SVG;
        }

        return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="{$width}" height="{$height}" viewBox="0 0 {$width} {$height}" role="img" aria-label="Apoie {$name} através da Kero Ajudar">
<defs><linearGradient id="bg" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#0f6b42"/><stop offset="1" stop-color="#29a866"/></linearGradient></defs>
<rect width="{$width}" height="{$height}" rx="12" fill="url(#bg)"/><circle cx="{$width}" cy="0" r="{$height}" fill="#ffffff" fill-opacity=".05"/>
<g font-family="Arial, Helvetica, sans-serif">{$content}<text x="{$this->brandX($format, $width)}" y="{$this->brandY($format, $height)}" text-anchor="{$this->brandAnchor($format)}" font-size="11" font-weight="700" fill="#ffffff" fill-opacity=".82">KERO AJUDAR</text></g>
</svg>
SVG;
    }

    private function initials(string $name): string
    {
        return Str::of($name)->explode(' ')->filter()->take(2)
            ->map(fn (string $word) => Str::upper(Str::substr($word, 0, 1)))->implode('');
    }

    private function nameLines(string $name, int $maxCharacters, int $maxLines): array
    {
        $words = preg_split('/\s+/u', trim($name)) ?: [];
        $lines = [];
        $line = '';
        foreach ($words as $index => $word) {
            $candidate = trim($line.' '.$word);
            if ($line !== '' && Str::length($candidate) > $maxCharacters) {
                $lines[] = $line;
                if (count($lines) === $maxLines - 1) {
                    $line = implode(' ', array_slice($words, $index));
                    break;
                }
                $line = $word;
            } else {
                $line = $candidate;
            }
        }
        if ($line !== '' && count($lines) < $maxLines) {
            $lines[] = Str::limit($line, $maxCharacters, '…');
        }

        return $lines ?: [$name];
    }

    private function lineText(array $lines, int $x, int $startY, int $gap, int $fontSize): string
    {
        return collect($lines)->values()->map(function (string $line, int $index) use ($x, $startY, $gap, $fontSize) {
            $y = $startY + ($index * $gap);
            return '<text x="'.$x.'" y="'.$y.'" text-anchor="middle" font-size="'.$fontSize.'" font-weight="800" fill="#ffffff">'.$this->xml($line).'</text>';
        })->implode('');
    }

    private function xml(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    private function brandX(string $format, int $width): int
    {
        return in_array($format, ['leaderboard', 'mobile-banner'], true) ? $width - 12 : (int) ($width / 2);
    }

    private function brandY(string $format, int $height): int
    {
        return in_array($format, ['leaderboard', 'mobile-banner'], true) ? $height - 8 : $height - 16;
    }

    private function brandAnchor(string $format): string
    {
        return in_array($format, ['leaderboard', 'mobile-banner'], true) ? 'end' : 'middle';
    }
}
