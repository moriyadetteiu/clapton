<?php

namespace App\UseCase\CircleProduct;

use App\Models\CircleProduct;
use App\UseCase\UseCase;

class UpdateCircleProduct extends UseCase
{
    public function execute(UpdateCircleProductInput $input)
    {
        $circleProductData = $input->toArray();
        $circleProduct = CircleProduct::findOrFail($circleProductData['id']);
        $circleProduct->update($circleProductData);
        $circleProduct->refresh();
        return $circleProduct;
    }
}
