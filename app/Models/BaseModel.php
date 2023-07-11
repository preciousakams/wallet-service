<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function saveData(array $data)
    {
        if(empty($data)) {
            return;
        }

        foreach($data as $key => $value) {
            if(is_array($value)) {
                $this->{$key} = json_encode($value);
                continue;
            }

            $this->{$key} = $value;
        }

        $this->save();

        return $this->toArray();
    }
}
