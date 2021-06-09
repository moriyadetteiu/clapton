<?php

namespace App\UseCase\WantCircleProduct;

use App\Models\WantCircleProduct;
use App\UseCase\UseCase;

class UpdateWantCircleProduct extends UseCase
{
    public function execute(UpdateWantCircleProductInput $input)
    {
        $wantCircleProductData = $input->toArray();
        $wantCircleProduct = WantCircleProduct::find($wantCircleProductData['id']);
        $wantCircleProduct->update($wantCircleProductData);
        $wantCircleProduct->refresh();
        return $wantCircleProduct;
    }
}
