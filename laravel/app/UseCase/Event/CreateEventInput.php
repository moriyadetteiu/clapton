<?php

namespace App\UseCase\Event;

use Illuminate\Support\Arr;

use App\UseCase\UseCaseInput;

class CreateEventInput extends UseCaseInput
{
    protected function rules(): array
    {
        return [
            'name' => 'required',
            'team_id' => 'required',
            'event_dates' => 'required'
        ];
    }

    protected function attributes(): array
    {
        return [
            'name' => 'イベント名',
            'team_id' => 'チーム番号',
            'event_dates' => '日付'
        ];
    }

    public function getEventData(): array
    {
        $data = $this->toArray();
        return Arr::except($data, ['team_id', 'event_dates']);
    }

    public function getTeamId(): string
    {
        return $this->toArray()['team_id'];
    }

    public function getEventDates(): array
    {
        return $this->toArray()['event_dates'];
    }
}
