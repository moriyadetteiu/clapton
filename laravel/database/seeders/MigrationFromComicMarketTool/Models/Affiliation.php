<?php

namespace Database\Seeders\MigrationFromComicMarketTool\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Database\Seeders\MigrationFromComicMarketTool\IdMapper;

class Affiliation extends Model
{
    use SoftDeletes;

    protected array $columnMapping = [
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ];

    protected array $relationMapping = [
        'group_id' => 'team_id',
        'user_id' => 'user_id',
    ];

    protected function appendAttributes(IdMapper $idMapper): array
    {
        $isAdmin = $this->post === '管理者';
        return ['is_admin' => $isAdmin];
    }
}
