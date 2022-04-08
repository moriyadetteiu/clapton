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
        $careAboutCircle = $this->findCareAboutCircle($input);
        $wantCircleProductData['care_about_circle_id'] = $careAboutCircle->id;

        $wantCircleProduct = WantCircleProduct::create($wantCircleProductData);
        return $wantCircleProduct;
    }

    private function findCareAboutCircle(CreateWantCircleProductInput $input)
    {
        $circlePlacementId = CircleProduct::findOrFail($input->toArray()['circle_product_id'])
            ->circlePlacement
            ->id;

        return CareAboutCircle::where('circle_placement_id', $circlePlacementId)
            ->where('join_event_id', $input->toArray()['join_event_id'])
            ->firstOrFail();
    }
}
