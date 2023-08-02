<?php
return [
    'baseUrl' => env('SPECTA_BASE_URL', 'https://paywithspectaapi.sterling.ng'),
    'apiKey' => env('SPECTA_API_KEY', 'TEST_API_KEY'),
    'paymentUri' => '/api/Purchase/CreatePaymentUrl',
    'verifyUri' => '/api/Purchase/VerifyPurchase',
    'confirmUri' => '/api/Purchase/VerifyPurchaseByReference?reference=',
    'merchantId' => '110675'
];
