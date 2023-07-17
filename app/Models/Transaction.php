<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use UUID;
    use SoftDeletes;

    protected $hidden = [
        'deleted_at',
    ];

    public function transactionable()
    {
        return $this->morphTo();
    }

    protected $casts = [
        'meta' => 'json'
    ];
}
