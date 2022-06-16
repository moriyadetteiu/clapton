<?php

namespace App\UseCase\WantCircleProduct;

use App\Models\{
    CareAboutCircle,
    CircleProduct,
    WantCircleProduct,
};
use App\UseCase\UseCase;
use App\UseCase\CareAboutCircle\CreateCareAboutCircle;
use App\UseCase\CareAboutCircle\CreateCareAboutCircleInput;

class CreateWantCircleProduct extends UseCase
{
    public function execute(CreateWantCircleProductInput $input)
    {
        $wantCircleProductData = $input->toArray();
        $careAboutCircle = $this->findOrCreateCareAboutCircle($input);
        $wantCircleProductData['care_about_circle_id'] = $careAboutCircle->id;

        $wantCircleProduct = WantCircleProduct::create($wantCircleProductData);
        return $wantCircleProduct;
    }

    private function findOrCreateCareAboutCircle(CreateWantCircleProductInput $input): CareAboutCircle
    {
        $circlePlacementId = CircleProduct::findOrFail($input->toArray()['circle_product_id'])
            ->circlePlacement
            ->id;

        $careAboutCircle = CareAboutCircle::where('circle_placement_id', $circlePlacementId)
            ->where('join_event_id', $input->toArray()['join_event_id'])
            ->first();

        if ($careAboutCircle) {
            return $careAboutCircle;
        }

        return $this->createCareAboutCircle($input);
    }

    private function createCareAboutCircle(CreateWantCircleProductInput $input)
    {
        $input = new CreateCareAboutCircleInput([
            'circle_placement_id' => $input->toArray()['circle_product_id'],
            'join_event_id' => $input->toArray()['join_event_id'],
        ]);
        return (new CreateCareAboutCircle())->execute($input);
    }
}
