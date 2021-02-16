<?php

namespace App\GraphQL\Mutations;

use App\UseCase\JoinEvent\CreateJoinEvent as CreateJoinEventUseCase;
use App\UseCase\JoinEvent\CreateJoinEventInput;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CreateJoinEvent
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $userId = Auth::id();
        $data['user_id'] = $userId;
        $input = new CreateJoinEventInput($data);
        return (new CreateJoinEventUseCase())->execute($input);
    }
}
