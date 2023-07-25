<?php


namespace App\services;


use App\Models\ExternalRequest;
use Illuminate\Support\Facades\Http;

class SpectaService extends BaseService
{
    const NARRATION = 'Specta Credit for %s';
    private array $_headers;
    private string $_payment_url;
    private string $_verify_url;

    public function __construct()
    {
        $this->serviceName = 'specta';
        $this->_payment_url = config('specta.baseUrl') . config('specta.paymentUri');
        $this->_verify_url = config('specta.baseUrl') . config('specta.verifyUri');
        $this->setHeaders();
    }

    public function createPaymentUrl($reference, $amount, $user_id, $description = '')
    {
        $payload = [
            'reference' => $reference,
            'amount' => (double)$amount,
            'description' => $description,
            'merchantId' => config('specta.merchantId'),
            'callBackUrl' => config('app.url') . '/api/external/specta/confirm/' . $reference
        ];

        $log = ExternalRequest::log(
            $this->serviceName,
            'createPaymentUrl',
            $this->_payment_url,
            null,
            $user_id,
            json_encode($payload)
        );

        $response = Http::withHeaders($this->_headers)->post($this->_payment_url, $payload);
        $log->response = $response->body();
        $log->update();

        $data = (object)json_decode($response->body(), true);

        if(!$data->success) {
            return new ServiceResponse(400, $data->error['message']);
        }

        $split_url = explode('=', $data->result);
        $token = urldecode($split_url[1]);

        cache()->set('specta_token_' . $reference, json_encode([
            'token' => $token,
            'user_id' => $user_id
        ]), 3600);

        return new ServiceResponse(200, '', ['payUrl' => $data->result]);
    }

    public function verifyPayment($ref, $reference)
    {
        $data = json_decode(cache()->get('specta_token_' . $reference));

        $payload = [
            'verificationToken' => $data->token
        ];

        $log = ExternalRequest::log(
            $this->serviceName,
            'verifyPayment',
            $this->_verify_url,
            null,
            $data->user_id,
            json_encode($payload)
        );

        $response = Http::withHeaders($this->_headers)->post($this->_verify_url, $payload);
        $log->response = $response->body();
        $log->update();

        $responseData = (object)json_decode($response->body(), true);

        if(!$responseData->success) {
            return new ServiceResponse(400, $responseData->error['message']);
        }

        return new ServiceResponse(200, '', [
            'reference' => $reference,
            'paymentReference' => $responseData->result['data']['paymentReference'],
            'user_id' => $data->user_id,
            'amount' => $responseData->result['data']['amountSettled']
        ]);
    }

    private function setHeaders()
    {
        $this->_headers = [
            'x-ApiKey' => config('specta.apiKey')
        ];
    }
}
