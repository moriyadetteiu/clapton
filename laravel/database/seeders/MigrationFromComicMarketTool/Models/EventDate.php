<?php

namespace Database\Seeders\MigrationFromComicMarketTool\Models;

use Database\Seeders\MigrationFromComicMarketTool\IdMapper;

class EventDate extends Model
{
    protected array $columnMapping = [
        'date' => 'date',
        'is_that_day' => 'is_production_day',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',

    ];

    protected array $relationMapping = [
        'event_id' => 'event_id',
    ];

    protected function appendAttributes(IdMapper $idMapper): array
    {
        $nameMap = [
            '一日目' => '1日目',
            '二日目' => '2日目',
            '三日目' => '3日目',
            '四日目' => '4日目',
        ];
        $name = str_replace(array_keys($nameMap), array_values($nameMap), $this->name);

        return [
            'name' => $name,
        ];
    }

    public function shouldIgnore(IdMapper $idMapper): bool
    {
        return $this->date === '0000-00-00';
    }
}
