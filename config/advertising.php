<?php

return [
    'enabled' => env('AD_CENTER_ENABLED', true),

    'categories' => [
        'animals' => 'Animais',
        'firefighters' => 'Bombeiros',
        'people' => 'Pessoas',
    ],

    'regions' => [
        'aveiro' => 'Aveiro',
        'beja' => 'Beja',
        'braga' => 'Braga',
        'braganca' => 'Bragança',
        'castelo-branco' => 'Castelo Branco',
        'coimbra' => 'Coimbra',
        'evora' => 'Évora',
        'faro' => 'Faro',
        'guarda' => 'Guarda',
        'leiria' => 'Leiria',
        'lisboa' => 'Lisboa',
        'portalegre' => 'Portalegre',
        'porto' => 'Porto',
        'santarem' => 'Santarém',
        'setubal' => 'Setúbal',
        'viana-do-castelo' => 'Viana do Castelo',
        'vila-real' => 'Vila Real',
        'viseu' => 'Viseu',
        'acores' => 'Açores',
        'madeira' => 'Madeira',
    ],

    'adsense' => [
        'enabled' => env('ADSENSE_ENABLED', false),
        'client' => env('ADSENSE_CLIENT'),
        'slots' => [
            'home-01' => env('ADSENSE_SLOT_HOME_01'),
            'home-02' => env('ADSENSE_SLOT_HOME_02'),
            'home-03' => env('ADSENSE_SLOT_HOME_03'),
            'home-04' => env('ADSENSE_SLOT_HOME_04'),
            'home-05' => env('ADSENSE_SLOT_HOME_05'),
            'home-06' => env('ADSENSE_SLOT_HOME_06'),
            'home-07' => env('ADSENSE_SLOT_HOME_07'),
            'home-08' => env('ADSENSE_SLOT_HOME_08'),
            'home-09' => env('ADSENSE_SLOT_HOME_09'),
            'home-10' => env('ADSENSE_SLOT_HOME_10'),
        ],
    ],
];
