<?php

namespace App\Models\IzyInvest;

use App\Models\BaseModel;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Request;

class IzyInvestUserPlan extends BaseModel
{
    use UUID;
    use SoftDeletes;

    protected $table = 'izyinvest_user_plans';

    protected $hidden = [
        'user_id', 'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(IzyInvestTransaction::class);
    }

    public function plan()
    {
        return $this->belongsTo(IzyInvestPlan::class, 'izyinvest_plan_id', 'id');
    }

    public function getActiveUserPlanWithId($id)
    {
        return $this->with('plan')->where([
            ['id', '=', $id],
            ['user_id', '=', request()->user()->id],
            ['active', '=', 1]
        ])->first();
    }
}
