<?php


namespace App\services;


use App\Models\ExternalRequest;
use App\Models\User;
use Http;

class MonnifyService
{
    private string $_baseUrl;
    private string $_apiKey;
    private string $_secret;
    private string $_contractCode;
    private string $_auth_url;
    private string $_reserve_url;
    private string $_update_bvn_url;
    private string $_accessToken;
    private array $_headers;

    public const ERROR_STATUS_CODE = '400';
    public const UNABLE_TO_CONFIRM_TRANSACTION = 'Unable to Validate Transaction';
    public const SUCCESSFUL_CODE = '0';
    public const SERVICE_NAME = 'monnify';
    public const TRANSFER_NARRATION = 'transfer from %s|%s';

    public function __construct()
    {
        $this->_baseUrl = config('monnify.baseUrl');
        $this->_reserve_url = $this->_baseUrl . config('monnify.reserve_uri');
        $this->_update_bvn_url = config('monnify.update_bvn_uri');
        $this->_auth_url = $this->_baseUrl . config('monnify.auth_uri');
        $this->_apiKey = config('monnify.apiKey');
        $this->_secret = config('monnify.secret');
        $this->_contractCode = config('monnify.contractCode');


        $response = $this->authenticate();

        if($response instanceof ServiceResponse) {
            throw new \Exception($response->getMessage());
        }
    }

    public function reserveAccount(User $user):ServiceResponse
    {
        $payload = [
            "accountReference" => $user->izyPay->id,
            "accountName" => $user->first_name . " " . $user->last_name,
            "currencyCode" => "NGN",
            "contractCode" => $this->_contractCode,
            "customerEmail" => $user->email,
            "customerName" => $user->first_name . " " . $user->last_name,
            "getAllAvailableBanks" => false,
            "preferredBanks" => ["035"]
        ];

        if(!empty($user->bvn)) {
            $payload['bvn'] = $user->bvn;
        }

        $log = ExternalRequest::log(
            self::SERVICE_NAME,
            'reserve',
            $this->_reserve_url,
            null,
            $user->id,
            json_encode($payload)
        );

        $response = Http::withHeaders($this->_headers)->post($this->_reserve_url, $payload);
        $log->response = $response->body();
        $log->update();

        $data = json_decode($response->body(), true);

        if(!$data['requestSuccessful']) {
            return new ServiceResponse($data['responseCode'], $data['responseMessage']);
        }

        $responseData = [
            'accountName' => $data['responseBody']['accountName'],
            'accounts' => $data['responseBody']['accounts']
        ];

        return new ServiceResponse($data['responseCode'], 'Account Reserved', $responseData);
    }

    /**
     * @param User $user
     * @return ServiceResponse
     */
    public function updateReservedAccountWithBVN(User $user): ServiceResponse
    {
        if(empty($user->bvn)) {
            return new ServiceResponse(NINETY, 'User has no BVN');
        }

        $payload = [
            'bvn' => $user->bvn
        ];

        $response = Http::withHeaders($this->_headers)->post($this->_update_bvn_url, $payload);
        $data = json_decode($response->body(), true);

        if(!$data['requestSuccessful']) {
            return new ServiceResponse($data['responseCode'], $data['responseMessage']);
        }

        return new ServiceResponse($data['responseCode'], 'Account Updated', (array)$data['responseBody']);
    }

    private function authenticate()
    {
        try {
            $log = ExternalRequest::log(self::SERVICE_NAME, 'auth', $this->_auth_url);

            $response = Http::withBasicAuth($this->_apiKey, $this->_secret)->post($this->_auth_url);
            $log->response = $response;
            $log->update();


            $data = json_decode($response->body(), true);

            if(empty($data['responseBody'])) {
                $log->response = $response->body();
                $log->update();

                return new ServiceResponse('400', 'There was an error generating a virtual account');
            }

            $this->_accessToken = $data['responseBody']['accessToken'];

            $this->setHeaders();
        } catch (\Exception $e) {
            $log->response = $e->getMessage();
            $log->update();

            return new ServiceResponse('400', $e->getMessage());
        }
    }

    private function setHeaders()
    {
        $this->_headers = [
            'Authorization' => 'Bearer ' . $this->_accessToken
        ];
    }

    public function validateHash($data)
    {
        return hash('sha512', sprintf(
            '%s|%s|%s|%s|%s',
            $this->_secret,
            $data->paymentReference,
            $data->amountPaid,
            $data->paidOn,
            $data->transactionReference
        )) == $data->transactionHash;
    }
}
