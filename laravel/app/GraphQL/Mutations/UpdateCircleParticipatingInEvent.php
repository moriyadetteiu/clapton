<?php

namespace App\GraphQL\Mutations;

use Auth;

use App\UseCase\Circle\UpdateCircleParticipatingInEvent as UpdateCircleParticipatingInEventUseCase;
use App\UseCase\Circle\UpdateCircleParticipatingInEventInput;
use Illuminate\Support\Arr;

class UpdateCircleParticipatingInEvent
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $data['operation_user_id'] = Auth::id();
        $input = new UpdateCircleParticipatingInEventInput($data);
        return (new UpdateCircleParticipatingInEventUseCase())->execute($input);
    }
}
