<?php

namespace App\UseCase\CircleProduct;

use App\Models\CareAboutCircle;
use App\Models\CircleProduct;
use App\UseCase\UseCase;

class UnnecessaryCircleProduct extends UseCase
{
    public function execute(UnnecessaryCircleProductInput $input)
    {
        $circleProduct = CircleProduct::findOrFail($input->get('id'));
        $careAboutCircle = $this->findCareAboutCircleByOperationUser($input->get('operation_user_id'), $circleProduct);

        $wantCircleProducts = $circleProduct
            ->wantCircleProducts()
            ->where('care_about_circle_id', $careAboutCircle->id)
            ->get()
            ->each(
                fn ($wantCircleProduct) => $wantCircleProduct->delete()
            );

        if (!$circleProduct->wantCircleProducts()->exists()) {
            $circleProduct->delete();
        }

        return $circleProduct;
    }

    private function findCareAboutCircleByOperationUser(string $operationUserId, CircleProduct $circleProduct): CareAboutCircle
    {
        return $circleProduct
            ->circlePlacement
            ->careAboutCircles()
            ->whereHasUser($operationUserId)
            ->firstOrFail();
    }
}
