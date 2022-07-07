<?php

namespace App\GraphQL\Mutations;

use App\UseCase\Circle\CreateCircleParticipatingInEvent as CreateCircleParticipatingInEventUseCase;
use App\UseCase\Circle\CreateCircleParticipatingInEventInput;
use Illuminate\Support\Arr;

class CreateCircleParticipatingInEvent
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $data['force'] = $args['force'] ?? false;
        $input = new CreateCircleParticipatingInEventInput($data);
        return (new CreateCircleParticipatingInEventUseCase())->execute($input);
    }
}
