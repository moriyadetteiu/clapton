<?php

namespace App\UseCase\WantCircleProduct;

use App\Models\{
    CareAboutCircle,
    CircleProduct,
    WantCircleProduct,
};
use App\UseCase\UseCase;

class CreateWantCircleProduct extends UseCase
{
    public function execute(CreateWantCircleProductInput $input)
    {
        $wantCircleProductData = $input->toArray();
        $wantCircleProduct = WantCircleProduct::create($wantCircleProductData);
        return $wantCircleProduct;
    }
}
