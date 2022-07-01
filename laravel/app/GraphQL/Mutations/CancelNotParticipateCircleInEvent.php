<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Arr;

use App\UseCase\Circle\CancelNotParticipateCircleInEvent as CancelNotParticipateCircleInEventUseCase;
use App\UseCase\Circle\CancelNotParticipateCircleInEventInput;

class CancelNotParticipateCircleInEvent
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = new CancelNotParticipateCircleInEventInput($data);
        return (new CancelNotParticipateCircleInEventUseCase())->execute($input)->circle;
    }
}
