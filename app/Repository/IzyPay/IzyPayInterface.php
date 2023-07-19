<?php


namespace App\Repository\IzyPay;
use App\Models\User;
use Illuminate\Http\Request;

interface IzyPayInterface
{

    /**
     * @return mixed
     */
    public function getBalance();

    public function transfer(User $receiver, $amount);

    public function externalWithdraw($amount, $details);

    public function validatePaystackTransaction(string $reference);

    public function deposit(User $receiver, $amount, $type='deposit', $meta=[], $reference=null);

    public function canWithdraw($amount);

    public function withdraw(User $debtor, $amount, $type, $meta=[], $reference=null, $shouldAccept=true);

    public function forceWithdraw($amount, $type, $meta=[]): void;

    public function reserveAccount();

    public function confirmTransfer($data);

    public function payWithSpecta($data);

    public function confirmSpectaPayment($data, $reference);
}
