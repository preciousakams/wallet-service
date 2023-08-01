<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'public_key' => env('PAYSTACK_PUBLIC_KEY', 'pk_test_a4cf569315de181f51f627e2a224f50fd8cc1d4f'),
    'secret_key' => env('PAYSTACK_SECRET_KEY', 'sk_test_0e9ee5c8f35066facc1dd3b2761b68494730031a'),
    'payment_url' => env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co'),
    'verify_url' => env('PAYSTACK_VERIFY_URL', 'https://api.paystack.co/transaction/verify/'),
    'merchant_email' => env('PAYSTACK_MERCHANT_EMAIL', 'https://api.paystack.co/transaction/verify/'),

];
