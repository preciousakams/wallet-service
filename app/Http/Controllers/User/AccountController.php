<?php

namespace App\Http\Controllers\User;

use App\Models\Bank;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Account\ValidateNubanRequest;
use Http as HttpClient;
use Illuminate\Http\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class AccountController extends Controller
{
    private Bank $bank;
    private HttpClient $httpClient;

    public function __construct(Bank $bank, HttpClient $httpClient)
    {
        $this->bank = $bank;
        $this->httpClient = $httpClient;
    }

    public function getBanks()
    {
        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Banks Listing')
            ->withData(['banks' => $this->bank::all()])
            ->build();
    }

    public function validateNuban(ValidateNubanRequest $request)
    {
        $url = "https://api.paystack.co/bank/resolve";
        $response = $this->httpClient::withToken(config('paystack.secret_key'))->get($url, [
            'account_number' => $request->account_number,
            'bank_code' => $request->bank_code
        ]);
        $body = json_decode($response->body());
        if ($body->status) {
            return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage($body->message)
            ->withData([
                'account_number' => $body->data->account_number,
                'account_name' => $body->data->account_name
            ])
            ->build();
        }

        return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
            ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->withMessage($body->message)
            ->build();
    }
}
