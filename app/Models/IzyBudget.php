<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IzyBudget extends Model
{
    use UUID;
    use SoftDeletes;

    protected $table = 'izybudget';

    protected $hidden = [
        'user_id', 'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class,'transactionable');
    }
}
