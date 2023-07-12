<?php

namespace App\Models\IzyInvest;

use App\Models\BaseModel;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IzyInvestPlan extends BaseModel
{
    use UUID;
    use SoftDeletes;

    protected $table = 'izyinvest_plans';
    protected $guarded = ['created_at', 'updated_at'];

    protected $hidden = [
        'deleted_at',
    ];
}
