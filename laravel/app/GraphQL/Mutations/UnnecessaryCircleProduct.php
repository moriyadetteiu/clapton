<?php

namespace App\GraphQL\Mutations;

use Auth;

use Illuminate\Support\Arr;

use App\UseCase\CircleProduct\UnnecessaryCircleProduct as UnnecessaryCircleProductUseCase;
use App\UseCase\CircleProduct\UnnecessaryCircleProductInput;

class UnnecessaryCircleProduct
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $data['operation_user_id'] = Auth::id();
        $input = new UnnecessaryCircleProductInput($data);
        return (new UnnecessaryCircleProductUseCase())->execute($input);
    }
}
