<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\IzyPay;
use App\Http\Requests\Administrator\IzyPay\DepositUserRequest;
use App\Repository\IzyPay\IzyPayService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class IzyPayController extends Controller
{
    private IzyPay $izyPay;
    private User $user;
    private IzyPayService $izyPayService;

    /**
     * IzyPayController constructor.
     * @param IzyPay $izyPay
     * @param User $user
     * @param IzyPayService $izyPayService
     */
    public function __construct(IzyPay $izyPay, User $user, IzyPayService $izyPayService)
    {
        $this->izyPay = $izyPay;
        $this->user = $user;
        $this->izyPayService = $izyPayService;
    }

    public function index()
    {
        $izypay = $this->izyPay->with('user')->paginate(20);

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withData(['izypay' => $izypay, 'total_value' => $this->izyPay->sum('balance')])
            ->build();
    }

    public function details(User $user)
    {
        $izyPay = $user->izyPay;

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withData([
                'izypay' => $izyPay,
                'transaction' => $izyPay->transactions()->latest()->get()
            ])
            ->build();
    }

    public function deposit(DepositUserRequest $request)
    {
        $user = $this->user->where('email',$request->email)->firstOrFail();

        if ($request->amount < 1) {
            return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->withMessage('Invalid amount')
                ->build();
        }

        $this->izyPayService->deposit($user, $request->amount, 'deposit', [
            'details' => 'Received the sum of ' . number_format($request->amount,2) . ' from ' . $request->user()->name,
        ]);

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withData(['izypay' => $user->izyPay])
            ->build();
    }
}
