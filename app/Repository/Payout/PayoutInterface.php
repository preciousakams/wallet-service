<?php

namespace App\Repository\Payout;

use App\Models\User;

interface PayoutInterface
{
    public function initializePayout(User $referral, array $paymentData);
}
