<?php

namespace Database\Seeders\MigrationFromComicMarketTool\Models;

use Illuminate\Database\Eloquent\Model;

class FinishedTableInformation extends Model
{
    protected $connection = 'mysql_comic_market_tool';
    // cspell:disable-next-line
    protected $table = 'finished_table_infomations';

    public function getFinishedTableNameAttribute(): string
    {
        return "{$this->table_name}_finished_{$this->event_id}_{$this->table_no}";
    }
}
