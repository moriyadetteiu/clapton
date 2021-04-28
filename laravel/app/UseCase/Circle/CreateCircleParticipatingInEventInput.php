<?php

namespace App\UseCase\Circle;

use App\UseCase\UseCaseInput;

class CreateCircleParticipatingInEventInput extends UseCaseInput
{
    protected function rules(): array
    {
        return [
            'circle' => 'required',
            'circle.name' => 'required',
            'placement' => 'required',
            'placement.event_date_id' => 'required',
            'placement.hole' => 'required',
            'placement.line' => 'required',
            'placement.number' => 'required',
            'placement.a_or_b' => 'required',
            'placement.circle_placement_classification_id' => 'required',
        ];
    }

    protected function attributes(): array
    {
        return [
            'circle' => 'サークル情報',
            'circle.name' => 'サークル名',
            'placement' => 'サークル配置情報',
            'placement.event_date_id' => '配置日',
            'placement.hole' => 'ホール',
            'placement.line' => '列',
            'placement.number' => '番号',
            'placement.a_or_b' => 'aかbか',
            'placement.circle_placement_classification_id' => '配置分類',
        ];
    }

    public function getCircleData(): array
    {
        return $this->toArray()['circle'];
    }

    public function getPlacementData(): array
    {
        return $this->toArray()['placement'];
    }
}
