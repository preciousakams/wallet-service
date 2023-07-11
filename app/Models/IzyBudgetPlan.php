<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IzyBudgetPlan extends Model
{
    use UUID;
    use SoftDeletes;

    protected $table = 'izybudget_plan';

    protected $hidden = [
        'deleted_at',
    ];

    public function izyBudgets()
    {
        return $this->hasMany(IzyBudget::class,'plan_id');
    }
}
