<?php

namespace App\GraphQL\Mutations;

use App\UseCase\CareAboutCircle\DontCareCircle as DontCareCircleUseCase;
use App\UseCase\CareAboutCircle\DontCareCircleInput;
use Illuminate\Support\Arr;

class DontCareCircle
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = new DontCareCircleInput($data);
        return (new DontCareCircleUseCase())->execute($input);
    }
}
