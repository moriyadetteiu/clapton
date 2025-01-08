<?php

namespace Database\Seeders\MigrationFromComicMarketTool\Models;

/**
 * Participationの移行先が2つあるため、仮想Modelとして実装
 * Modelと移行クラスは分けたほうがよかった...
 */
class ParticipationForJoinEventDate extends Model
{
    // cspell:disable-next-line
    protected $table = 'participations';

    protected array $columnMapping = [
        'tickets' => 'number_of_tickets',
        'is_join' => 'is_join',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ];

    protected array $relationMapping = [
        'id' => 'join_event_id',
        'event_date_id' => 'event_date_id',
    ];
}
