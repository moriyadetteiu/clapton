<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

use App\Models\Traits\PrimaryKeyUuidUsable;

abstract class Model extends BaseModel
{
    use PrimaryKeyUuidUsable;

    // primary key でuuidを使うためにoverrideする
    public $incrementing = false;
    protected $keyType = 'string';
}
