<?php

namespace App\GraphQL\Mutations;

use App\UseCase\WantPriority\CreateWantPriority as CreateWantPriorityUseCase;
use App\UseCase\WantPriority\CreateWantPriorityInput;
use Illuminate\Support\Arr;

class CreateWantPriority
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = new CreateWantPriorityInput($data);
        return (new CreateWantPriorityUseCase())->execute($input);
    }
}
