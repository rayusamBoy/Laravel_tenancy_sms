<?php
use App\Helpers\Qs;

return [
    'name' => 'LaravelPWA',
    'manifest' => [
        'name' => env('APP_NAME', 'School Management System'),
        'short_name' => Qs::getStringAbbreviation(env('APP_NAME', 'School Management System')),
        'start_url' => '/',
        'background_color' => '#ffffff',
        'theme_color' => '#000000',
        'display' => 'standalone',
        'orientation' => 'any',
        'status_bar' => 'black',
        'icons' => [
            '16x16' => [
                'path' => '/images/icons/icon-16x16.png',
                'purpose' => 'any'
            ],
            '20x20' => [
                'path' => '/images/icons/icon-20x20.png',
                'purpose' => 'any'
            ],
            '29x29' => [
                'path' => '/images/icons/icon-29x29.png',
                'purpose' => 'any'
            ],
            '30x30' => [
                'path' => '/images/icons/icon-30x30.png',
                'purpose' => 'any'
            ],
            '32x32' => [
                'path' => '/images/icons/icon-32x32.png',
                'purpose' => 'any'
            ],
            '36x36' => [
                'path' => '/images/icons/icon-36x36.png',
                'purpose' => 'any'
            ],
            '40x40' => [
                'path' => '/images/icons/icon-40x40.png',
                'purpose' => 'any'
            ],
            '44x44' => [
                'path' => '/images/icons/icon-44x44.png',
                'purpose' => 'any'
            ],
            '48x48' => [
                'path' => '/images/icons/icon-48x48.png',
                'purpose' => 'any'
            ],
            '50x50' => [
                'path' => '/images/icons/icon-50x50.png',
                'purpose' => 'any'
            ],
            '55x55' => [
                'path' => '/images/icons/icon-55x55.png',
                'purpose' => 'any'
            ],
            '57x57' => [
                'path' => '/images/icons/icon-57x57.png',
                'purpose' => 'any'
            ],
            '58x58' => [
                'path' => '/images/icons/icon-58x58.png',
                'purpose' => 'any'
            ],
            '60x60' => [
                'path' => '/images/icons/icon-60x60.png',
                'purpose' => 'any'
            ],
            '62x62' => [
                'path' => '/images/icons/icon-62x62.png',
                'purpose' => 'any'
            ],
            '64x64' => [
                'path' => '/images/icons/icon-64x64.png',
                'purpose' => 'any'
            ],
            '66x66' => [
                'path' => '/images/icons/icon-66x66.png',
                'purpose' => 'any'
            ],
            '70x70' => [
                'path' => '/images/icons/icon-70x70.png',
                'purpose' => 'any'
            ],
            '71x71' => [
                'path' => '/images/icons/icon-71x71.png',
                'purpose' => 'any'
            ],
            '72x72' => [
                'path' => '/images/icons/icon-72x72.png',
                'purpose' => 'any'
            ],
            '76x76' => [
                'path' => '/images/icons/icon-76x76.png',
                'purpose' => 'any'
            ], '80x80' => [
                'path' => '/images/icons/icon-80x80.png',
                'purpose' => 'any'
            ], '87x87' => [
                'path' => '/images/icons/icon-87x87.png',
                'purpose' => 'any'
            ], '88x88' => [
                'path' => '/images/icons/icon-88x88.png',
                'purpose' => 'any'
            ], '92x92' => [
                'path' => '/images/icons/icon-92x92.png',
                'purpose' => 'any'
            ],
            '96x96' => [
                'path' => '/images/icons/icon-96x96.png',
                'purpose' => 'any'
            ],
            '100x100' => [
                'path' => '/images/icons/icon-100x100.png',
                'purpose' => 'any'
            ],
            '114x114' => [
                'path' => '/images/icons/icon-114x114.png',
                'purpose' => 'any'
            ],
            '120x120' => [
                'path' => '/images/icons/icon-120x120.png',
                'purpose' => 'any'
            ],
            '128x128' => [
                'path' => '/images/icons/icon-128x128.png',
                'purpose' => 'any'
            ],
            '144x144' => [
                'path' => '/images/icons/icon-144x144.png',
                'purpose' => 'any'
            ],
            '150x150' => [
                'path' => '/images/icons/icon-150x150.png',
                'purpose' => 'any'
            ],
            '152x152' => [
                'path' => '/images/icons/icon-152x152.png',
                'purpose' => 'any'
            ],
            '167x167' => [
                'path' => '/images/icons/icon-167x167.png',
                'purpose' => 'any'
            ],
            '170x170' => [
                'path' => '/images/icons/icon-170x170.png',
                'purpose' => 'any'
            ],
            '172x172' => [
                'path' => '/images/icons/icon-172x172.png',
                'purpose' => 'any'
            ],
            '180x180' => [
                'path' => '/images/icons/icon-180x180.png',
                'purpose' => 'any'
            ],
            '192x192' => [
                'path' => '/images/icons/icon-192x192.png',
                'purpose' => 'any'
            ],
            '196x196' => [
                'path' => '/images/icons/icon-196x196.png',
                'purpose' => 'any'
            ],
            '216x216' => [
                'path' => '/images/icons/icon-216x216.png',
                'purpose' => 'any'
            ],
            '256x256' => [
                'path' => '/images/icons/icon-256x256.png',
                'purpose' => 'any'
            ],
            '300x300' => [
                'path' => '/images/icons/icon-300x300.png',
                'purpose' => 'any'
            ],
            '310x310' => [
                'path' => '/images/icons/icon-310x310.png',
                'purpose' => 'any'
            ],
            '358x358' => [
                'path' => '/images/icons/icon-358x358.png',
                'purpose' => 'any'
            ],
            '360x360' => [
                'path' => '/images/icons/icon-360x360.png',
                'purpose' => 'any'
            ],
            '384x384' => [
                'path' => '/images/icons/icon-384x384.png',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => '/images/icons/icon-512x512.png',
                'purpose' => 'any'
            ],
            '558x558' => [
                'path' => '/images/icons/icon-558x558.png',
                'purpose' => 'any'
            ],
            '1024x1024' => [
                'path' => '/images/icons/icon-1024x1024.png',
                'purpose' => 'any'
            ],
        ],
        'splash' => [
            '640x1136' => '/images/icons/splash-640x1136.png',
            '750x1334' => '/images/icons/splash-750x1334.png',
            '828x1792' => '/images/icons/splash-828x1792.png',
            '1125x2436' => '/images/icons/splash-1125x2436.png',
            '1136x640' => '/images/icons/splash-1136x640.png',
            '1170x2532' => '/images/icons/splash-1170x2532.png',
            '1179x2556' => '/images/icons/splash-1179x2556.png',
            '1242x2208' => '/images/icons/splash-1242x2208.png',
            '1242x2688' => '/images/icons/splash-1242x2688.png',
            '1284x2778' => '/images/icons/splash-1284x2778.png',
            '1290x2796' => '/images/icons/splash-1290x2796.png',
            '1536x2048' => '/images/icons/splash-1536x2048.png',
            '1334x750' => '/images/icons/splash-1334x750.png',
            '1488x2266' => '/images/icons/splash-1488x2266.png',
            '1620x2160' => '/images/icons/splash-1620x2160.png',
            '1640x2360' => '/images/icons/splash-1640x2360.png',
            '1668x2224' => '/images/icons/splash-1668x2224.png',
            '1668x2388' => '/images/icons/splash-1668x2388.png',
            '1792x828' => '/images/icons/splash-1792x828.png',
            '2048x2732' => '/images/icons/splash-2048x2732.png',
            '2160x1620' => '/images/icons/splash-2160x1620.png',
            '2208x1242' => '/images/icons/splash-2208x1242.png',
            '2224x1668' => '/images/icons/splash-2224x1668.png',
            '2266x1488' => '/images/icons/splash-2266x1488.png',
            '2360x1640' => '/images/icons/splash-2360x1640.png',
            '2388x1668' => '/images/icons/splash-2388x1668.png',
            '2436x1125' => '/images/icons/splash-2436x1125.png',
            '2532x1170' => '/images/icons/splash-2532x1170.png',
            '2256x1179' => '/images/icons/splash-2256x1179.png',
            '2688x1242' => '/images/icons/splash-2688x1242.png',
            '2732x2048' => '/images/icons/splash-2732x2048.png',
            '2778x1284' => '/images/icons/splash-2778x1284.png',
            '2796x1290' => '/images/icons/splash-2796x1290.png',
        ],
        'shortcuts' => [
            [
                'name' => 'Shortcut Link 1',
                'description' => 'Shortcut Link 1 Description',
                'url' => '/shortcutlink1',
                'icons' => [
                    "src" => "/images/icons/icon-72x72.png",
                    "purpose" => "any"
                ]
            ],
            [
                'name' => 'Shortcut Link 2',
                'description' => 'Shortcut Link 2 Description',
                'url' => '/shortcutlink2'
            ]
        ],
        'custom' => []
    ]
];
