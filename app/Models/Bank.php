<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'banks';

    protected $hidden = [
      'created_at', 'updated_at'
    ];

    public function referralAccounts()
    {
        return $this->hasMany(ReferralAccount::class);
    }
}
