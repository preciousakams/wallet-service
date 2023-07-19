<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class ReferralPayoutHistory extends Model
{
    use UUID;

    protected $guarded = ['id'];

    protected $table = 'referral_payout_histories';

    public function referral()
    {
        return $this->belongsTo(User::class, 'refferral_id');
    }
}
