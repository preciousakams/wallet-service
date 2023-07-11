<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalRequest extends Model
{
    protected $table = 'external_requests';

    protected $hidden = [
      'created_at', 'updated_at'
    ];

    public static function log(
        $service = null,
        $endpoint = null,
        $url = null,
        $reference = null,
        $user_id = null,
        $request = null,
        $response = null,
        $pay_ref = null
    )
    {
        $log = new ExternalRequest();
        $log->service = $service;
        $log->endpoint = $endpoint;
        $log->url = $url;
        $log->user_id = $user_id;
        $log->reference = $reference;
        $log->request =  $request;
        $log->response = $response;
        $log->pay_ref = $pay_ref;
        $log->save();

        return $log->refresh();
    }
}
