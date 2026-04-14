<?php

return [
    'env' => env('DATATRANS_ENV', 'sandbox'),
    'api_url' => env('DATATRANS_API_URL', 'https://api.sandbox.datatrans.com'),
    'merchant_id' => env('DATATRANS_MERCHANT_ID', ''),
    'api_password' => env('DATATRANS_API_PASSWORD', ''),
    'allowed_methods' => ['VIS', 'ECA', 'TWI'],
    'currency' => 'CHF',
];
