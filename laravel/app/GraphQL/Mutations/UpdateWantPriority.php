<?php

namespace App\GraphQL\Mutations;

use App\UseCase\WantPriority\UpdateWantPriority as UpdateWantPriorityUseCase;
use App\UseCase\WantPriority\UpdateWantPriorityInput;
use Illuminate\Support\Arr;

class UpdateWantPriority
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = new UpdateWantPriorityInput($data);
        return (new UpdateWantPriorityUseCase())->execute($input);
    }
}
