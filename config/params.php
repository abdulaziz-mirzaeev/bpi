<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'excelDB' => [
        'yearRow' => 5,
        'monthRow' => 4,
        'profitOrLoss' => [
            'startRow' => 7,
            'endRow' => 43,
        ],
        'balanceSheet' => [
            'startRow' => 45,
            'endRow' => 66,
        ],
    ],
    'company' => [
        'name' => 'Dybacco Constructions',
        'a2p_p&l' => [
            'thresholds' => [
                'dollarDifference' => [
                    'NET_SALES' => -200000,
                    'DIRECT_COSTS' => 50000,
                    'INDIRECT_COSTS' => 5000,
                    'NET_NONOPERATING_COSTS' => 5000,
                ],
            ],
        ],
        'yoy_p&l' => [
            'thresholds' => [
                'actualChangeInDollars' => [
                    'NET_SALES' => -200000,
                    'DIRECT_COSTS' => 100000,
                    'INDIRECT_COSTS' => 10000,
                    'NET_NONOPERATING_COSTS' => 10000,
                ],
                'salesPercentDifference' => [
                    'DIRECT_COSTS' => 0.05,
                    'INDIRECT_COSTS' => 0.01,
                    'NET_NONOPERATING_COSTS' => 0.01,
                ]
            ]
        ]
    ],

    // Krajee Settings
    'bsVersion' => '5.x',
];
