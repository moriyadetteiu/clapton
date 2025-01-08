<?php

namespace Database\Seeders\MigrationFromComicMarketTool\Models;

class Favorite extends Model
{
    protected array $columnMapping = [
        'memo' => 'memo',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ];

    protected array $relationMapping = [
        'circle_id' => 'circle_id',
        'user_id' => 'user_id',
    ];
}
