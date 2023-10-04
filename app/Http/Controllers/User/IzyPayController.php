<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\IzyPay\IzyPayTransferRequest;
use App\Http\Requests\User\WithdrawRequest;
use App\Repository\IzyPay\IzyPayInterface;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class IzyPayController extends Controller
{
    private IzyPayInterface $izyPayService;
    private User $user;

    public function __construct(IzyPayInterface $izyPayService, User $user)
    {
        $this->izyPayService = $izyPayService;
        $this->user = $user;
    }

    public function getBalance()
    {
        return $this->izyPayService->getBalance();
    }

    public function transfer(IzyPayTransferRequest $request)
    {
        $receiver = $this->user::where('liveizy_id',$request->user_liveizy)->firstOrFail();

        return $this->izyPayService->transfer($receiver, $request->amount);
    }

    public function withdraw(WithdrawRequest $request)
    {
        return $this->izyPayService->externalWithdraw($request->amount, $request->details);
    }

    public function verifyTransaction(Request $request)
    {
        return $this->izyPayService->validatePaystackTransaction($request->reference);
    }

    public function reserveAccount(Request $request)
    {
        return $this->izyPayService->reserveAccount();
    }

    public function confirmTransfer(Request $request)
    {
        return $this->izyPayService->confirmTransfer((object)$request->all());
    }

    public function payWithSpecta(Request $request)
    {
        return$this->izyPayService->payWithSpecta((object) $request->all());
    }

    public function confirmSpectaPayment(Request $request, $reference)
    {
        return $this->izyPayService->confirmSpectaPayment((object)$request->all(), $reference);
    }
}
