<?php

namespace App\GraphQL\Mutations;

use App\UseCase\JoinEvent\UpdateJoinEvent as UpdateJoinEventUseCase;
use App\UseCase\JoinEvent\UpdateJoinEventInput;
use Illuminate\Support\Arr;

class UpdateJoinEvent
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);

        $input = new UpdateJoinEventInput($data);
        return (new UpdateJoinEventUseCase())->execute($input);
    }
}
