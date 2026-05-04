<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use Illuminate\Database\Seeder;

class AdvertisementSeeder extends Seeder
{
    public function run()
    {
        $games = [
            [
                'title' => 'iPhone 17 em sorteio',
                'subtitle' => 'Participa com o teu donativo',
                'draw_date' => '2026-05-15',
                'sort_order' => 10,
            ],
            [
                'title' => 'Viagem a Madeira',
                'subtitle' => 'Fim de semana para duas pessoas',
                'draw_date' => '2026-08-15',
                'sort_order' => 20,
            ],
            [
                'title' => 'PlayStation 6',
                'subtitle' => 'Sorteio solidario especial',
                'draw_date' => '2026-09-30',
                'sort_order' => 30,
            ],
            [
                'title' => 'Cabaz tecnologico',
                'subtitle' => 'Tablet, smartwatch e auriculares',
                'draw_date' => '2026-11-20',
                'sort_order' => 40,
            ],
            [
                'title' => 'Experiencia spa',
                'subtitle' => 'Voucher premium para relaxar',
                'draw_date' => '2026-12-12',
                'sort_order' => 50,
            ],
        ];

        foreach ($games as $game) {
            Advertisement::updateOrCreate(
                ['type' => Advertisement::TYPE_GAME, 'title' => $game['title']],
                array_merge($game, [
                    'type' => Advertisement::TYPE_GAME,
                    'link_url' => url('/donativo'),
                    'active' => true,
                ])
            );
        }

        $sponsors = [
            ['title' => 'Banco Solidario', 'link_url' => 'https://example.com/banco-solidario', 'sort_order' => 10],
            ['title' => 'Clinica Vida', 'link_url' => 'https://example.com/clinica-vida', 'sort_order' => 20],
            ['title' => 'Tech4Good', 'link_url' => 'https://example.com/tech4good', 'sort_order' => 30],
            ['title' => 'Mercado Local', 'link_url' => 'https://example.com/mercado-local', 'sort_order' => 40],
            ['title' => 'Hotel Atlantico', 'link_url' => 'https://example.com/hotel-atlantico', 'sort_order' => 50],
            ['title' => 'Farmacia Central', 'link_url' => 'https://example.com/farmacia-central', 'sort_order' => 60],
            ['title' => 'Energia Verde', 'link_url' => 'https://example.com/energia-verde', 'sort_order' => 70],
            ['title' => 'Padaria Aurora', 'link_url' => 'https://example.com/padaria-aurora', 'sort_order' => 80],
        ];

        foreach ($sponsors as $sponsor) {
            Advertisement::updateOrCreate(
                ['type' => Advertisement::TYPE_SPONSOR, 'title' => $sponsor['title']],
                array_merge($sponsor, [
                    'type' => Advertisement::TYPE_SPONSOR,
                    'subtitle' => 'Sponsor oficial',
                    'draw_date' => null,
                    'active' => true,
                ])
            );
        }
    }
}
