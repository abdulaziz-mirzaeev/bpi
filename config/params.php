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
    ],

    // Krajee Settings
    'bsVersion' => '5.x',
];
