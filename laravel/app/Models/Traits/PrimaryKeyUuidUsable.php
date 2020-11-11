<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait PrimaryKeyUuidUsable
{
    protected static function bootPrimaryKeyUuidUsable()
    {
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string)Str::orderedUuid();
        });
    }
}
