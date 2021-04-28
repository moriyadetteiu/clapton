<?php

namespace App\GraphQL\Mutations;

use App\UseCase\CareAboutCircle\CreateCareAboutCircle as CreateCareAboutCircleUseCase;
use App\UseCase\CareAboutCircle\CreateCareAboutCircleInput;
use Illuminate\Support\Arr;

class CreateCareAboutCircle
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = new CreateCareAboutCircleInput($data);
        return (new CreateCareAboutCircleUseCase())->execute($input);
    }
}
