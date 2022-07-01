<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Arr;

use App\UseCase\Circle\NotParticipateCircleInEvent as NotParticipateCircleInEventUseCase;
use App\UseCase\Circle\NotParticipateCircleInEventInput;

class NotParticipateCircleInEvent
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = new NotParticipateCircleInEventInput($data);
        return (new NotParticipateCircleInEventUseCase())->execute($input)->circle;
    }
}
