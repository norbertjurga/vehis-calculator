<?php

return [    
    'max_car_value' => 400000,
    'min_car_production_year' => 1940,
    'new_car_treshold' => now()->subYears(1)->format("Y"),
    'installment_fixed_fee' => 200,
    'used_car_multiplier' => 1,
    'gps_multiplier' => 1.11,
    'gross_to_net_multiplier' => 1.23,
    'multiplier_tresholds' => [
        [
            'from' => 0,
            'to' => 40000,
            'value' => 8,
        ],
        [
            'from' => 40001,
            'to' => 100000,
            'value' => 5,
        ],
        [
            'from' => 100001,
            'to' => 200000,
            'value' => 4,
        ],
        [
            'from' => 200001,
            'to' => 400000,
            'value' => 2,
        ]
    ]
];  