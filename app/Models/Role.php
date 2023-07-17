<?php

namespace App\Models;

use App\Traits\UUID;
use \Spatie\Permission\Models\Role as BaseModel;

class Role extends BaseModel
{
    use UUID;
}
