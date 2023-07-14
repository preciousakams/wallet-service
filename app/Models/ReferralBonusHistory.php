<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class ReferralBonusHistory extends Model
{
    use UUID;

    protected $guarded = ['id'];

    protected $table = 'referral_bonus_histories';

    public function referral()
    {
        return $this->belongsTo(User::class, 'referral_id');
    }
}
