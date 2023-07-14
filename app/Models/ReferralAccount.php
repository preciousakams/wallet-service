<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferralAccount extends Model
{
    use UUID, SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'referral_account';

    protected $visible = [
        'bank_name', 'bank_id', 'account_number', 'account_name', 'balance'
    ];

    protected $appends = ['bank_name'];

    public function getBankNameAttribute()
    {
        return $this->bank->name;
    }


    public function bank()
    {
        return $this->belongsTo(Bank::class)
            ->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
