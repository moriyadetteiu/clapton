<?php

namespace App\UseCase\JoinEvent;

use Illuminate\Support\Arr;

use App\UseCase\UseCaseInput;

class CreateJoinEventInput extends UseCaseInput
{
    protected function rules(): array
    {
        return [
            'team_id' => 'required',
            'event_id' => 'required',
            'user_id' => 'required',
            'join_event_dates' => 'required',
            'join_event_dates.*.event_date_id' => 'required',
            'join_event_dates.*.is_join' => 'required',
        ];
    }

    protected function attributes(): array
    {
        return [
            'team_id' => 'チーム番号',
            'event_id' => 'イベント番号',
            'user_id' => 'ユーザ番号',
            'join_event_dates' => '参加日',
            'join_event_dates.*.event_date_id' => 'イベント日付番号',
            'join_event_dates.*.is_join' => '参加日（参加するかどうか）',
        ];
    }

    public function getJoinEventData(): array
    {
        return Arr::except($this->toArray(), ['join_event_dates']);
    }

    public function getJoinEventDates(): array
    {
        return $this->toArray()['join_event_dates'];
    }
}
