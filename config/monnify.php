<?php
return [
    'baseUrl' => env('MONNIFY_BASE_URL', 'https://sandbox.monnify.com/api'),
    'reserve_uri' => env('MONNIFY_RESERVE_URI', '/v2/bank-transfer/reserved-accounts'),
    'update_bvn_uri' => env('MONNIFY_UPDATE_BVN_URI', '/v1/bank-transfer/reserved-accounts/update-customer-bvn/'),
    'auth_uri' => env('MONNIFY_AUTH_URI', '/v1/auth/login'),
    'apiKey' => env('MONNIFY_API_KEY', 'MK_TEST_UGGZ3EZD8K'),
    'secret' => env('MONNIFY_SECRET', 'TQCL5YBL5DG6PVNJ4D9T453KB8CFT548'),
    'contractCode' => env('MONNIFY_CONTRACT_CODE', '7812192246')
];
