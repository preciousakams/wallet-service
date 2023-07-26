<?php
/**
 * Created by PhpStorm.
 * User: Immaculate
 * Date: 3/10/2019
 * Time: 4:06 PM
 */

return [


    /**
     * Gaps service
     *
     */
    'endpoint' => env('GAPS_ENDPOINT', 'https://ebank2.gtbank.com/GapsFileUploader_pilot/FileUploader.asmx'),
    'username' => env('GAPS_USERNAME', ''),
    'password' => env('GAPS_PASSWORD', ''),
    'access_code' => env('GAPS_ACCESS_CODE', ''),

    /**
     * Hash Algorithm
     *
     */
    'hash' => 'sha512',
];
