<?php

namespace Database\Seeders\MigrationFromComicMarketTool\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Circle extends Model
{
    use SoftDeletes;

    protected array $columnMapping = [
        'name' => 'name',
        'kana' => 'kana',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ];
}
