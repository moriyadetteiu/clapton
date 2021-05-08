<?php

namespace App\UseCase\CircleProduct;

use App\Models\CircleProduct;
use App\UseCase\UseCase;

class CreateCircleProduct extends UseCase
{
    public function execute(CreateCircleProductInput $input)
    {
        $circleProductData = $input->toArray();
        $circleProduct = CircleProduct::create($circleProductData);
        return $circleProduct;
    }
}
