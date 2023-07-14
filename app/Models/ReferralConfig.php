<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralConfig extends Model
{
	protected $guarded = ['id'];
	
    protected $table = 'referral_config';

    protected $visible = ['commission_percentage'];
}
