<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

/**
 * モデルを登録する際のid値の生成をuuidに変更する
 * このTraitを使う際には以下のプロパティをオーバーライドする必要があるので、注意すること
 * （trait内で、プロパティは定義しないほうがいいので、各クラスで行うこと）
 *
 * public $incrementing = false;
 * protected $keyType = 'string';
 */
trait PrimaryKeyUuidUsable
{
    protected static function bootPrimaryKeyUuidUsable()
    {
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string)Str::orderedUuid();
        });
    }
}
