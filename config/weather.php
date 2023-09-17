<?php

return [
    'forecast' => [
        'provider' => [
            'host' => env('FORECAST_PROVIDER_HOST', ''),
            'key'  => env('FORECAST_PROVIDER_KEY', ''),
            'urls' => [
                '5day' => env('FORECAST_PROVIDER_URL_5DAY', ''),
            ]
        ]
    ]
];