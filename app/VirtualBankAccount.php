<?php

namespace App;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Model;

class VirtualBankAccount extends Model
{
    protected $connection = 'mysql';

    protected $table = 'virtual_bank_accounts';

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public static function add($data)
    {
        $account = new self();

        foreach ($data as $col => $value) {
            $account->{$col} = $value;
        }

        $account->save();
    }
}
