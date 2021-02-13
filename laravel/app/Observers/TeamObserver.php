<?php

namespace App\Observers;

use App\Models\Team;
use App\UseCase\CirclePlacementClassification\CreateCirclePlacementClassification;
use App\UseCase\CirclePlacementClassification\CreateCirclePlacementClassificationInput;
use App\UseCase\CircleProductClassification\CreateCircleProductClassification;
use App\UseCase\CircleProductClassification\CreateCircleProductClassificationInput;
use App\UseCase\WantPriority\CreateWantPriority;
use App\UseCase\WantPriority\CreateWantPriorityInput;

class TeamObserver
{
    private const INITIAL_CIRCLE_PLACEMENT_CLASSIFICATION_VALUES = [
        ['name' => 'シャッター', 'cost' => 0],
        ['name' => 'シャッター脇', 'cost' => 0],
        ['name' => '壁', 'cost' => 0],
        ['name' => '島壁', 'cost' => 0],
        ['name' => '中央通路', 'cost' => 0],
        ['name' => 'お誕生日', 'cost' => 0],
        ['name' => '島', 'cost' => 0],
    ];

    private const INITIAL_CIRCLE_PRODUCT_CLASSIFICATION_VALUES = [
        ['name' => '新刊'],
        ['name' => '既刊'],
        ['name' => 'グッズセット'],
        ['name' => 'グッズ類'],
        ['name' => 'CD類'],
    ];

    private const INITIAL_WANT_PRIORITY_VALUES = [
        ['name' => '最高'],
        ['name' => '高'],
        ['name' => '中'],
        ['name' => '低']
    ];

    public function created(Team $team)
    {
        collect(self::INITIAL_CIRCLE_PLACEMENT_CLASSIFICATION_VALUES)->each(function ($value) use ($team) {
            $value['team_id'] = $team->id;
            $input = new CreateCirclePlacementClassificationInput($value);
            $useCase = new CreateCirclePlacementClassification();
            $useCase->execute($input);
        });

        collect(self::INITIAL_CIRCLE_PRODUCT_CLASSIFICATION_VALUES)->each(function ($value) use ($team) {
            $value['team_id'] = $team->id;
            $input = new CreateCircleProductClassificationInput($value);
            $useCase = new CreateCircleProductClassification();
            $useCase->execute($input);
        });

        collect(self::INITIAL_WANT_PRIORITY_VALUES)->each(function ($value) use ($team) {
            $value['team_id'] = $team->id;
            $input = new CreateWantPriorityInput($value);
            $useCase = new CreateWantPriority();
            $useCase->execute($input);
        });
    }
}
