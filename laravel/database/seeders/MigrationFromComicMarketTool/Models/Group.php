<?php

namespace Database\Seeders\MigrationFromComicMarketTool\Models;

class Group extends Model
{
    protected array $columnMapping = [
        'name' => 'name',
        'code' => 'code',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ];
}
