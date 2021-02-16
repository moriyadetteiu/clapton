<?php

namespace App\UseCase\JoinEvent;

class UpdateJoinEventInput extends CreateJoinEventInput
{
    protected function rules(): array
    {
        return [
            'id' => 'required',
            'join_event_dates' => 'required',
            'join_event_dates.*.event_date_id' => 'required',
            'join_event_dates.*.is_join' => 'required',
        ];
    }

    protected function attributes(): array
    {
        $attributes = parent::attributes();

        $attributes['id'] = '参加情報番号';

        return $attributes;
    }

    public function getId(): string
    {
        return $this->toArray()['id'];
    }
}
