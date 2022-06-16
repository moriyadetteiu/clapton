<?php

namespace App\UseCase\CircleProduct;

use App\Models\CircleProduct;
use App\UseCase\UseCase;
use App\UseCase\WantCircleProduct\CreateWantCircleProduct;
use App\UseCase\WantCircleProduct\CreateWantCircleProductInput;

class UpdateCircleProduct extends UseCase
{
    public function execute(UpdateCircleProductInput $input)
    {
        $circleProductData = $input->toArray();
        $circleProduct = CircleProduct::findOrFail($circleProductData['id']);

        // note: 名前が変更されていて、他ユーザもほしいものに登録されている頒布物の場合は、
        //       更新してしまうと他ユーザのほしいものも一緒に変更されてしまうため、
        //       直接更新せずに、新規で頒布物を登録し、ほしいもののレコードは新しい頒布物に付け替える形にしている
        $operationUserId = $input->get('operation_user_id');
        $isNameChanged = $input->get('name') !== $circleProduct->name;
        if ($isNameChanged && $this->isExistsWantCircleProductWhereHasOtherUser($circleProduct)) {
            $this->renewWantCircleProduct($operationUserId, $input, $circleProduct);
        } else {
            $circleProduct->update($circleProductData);
        }

        $circleProduct->refresh();
        return $circleProduct;
    }

    private function isExistsWantCircleProductWhereHasOtherUser(CircleProduct $circleProduct): bool
    {
        return $circleProduct->wantCircleProducts()->count() > 1;
    }

    private function renewWantCircleProduct(string $operationUserId, UpdateCircleProductInput $input, CircleProduct $circleProduct): CircleProduct
    {
        $operationUserWantCircleProduct = $circleProduct
            ->wantCircleProducts()
            ->whereHasUser($operationUserId)
            ->firstOrFail();

        $operationUserWantCircleProduct->delete();

        $createCircleProductInput = new CreateCircleProductInput($input->toArray());
        $circleProduct = (new CreateCircleProduct())->execute($createCircleProductInput);

        $createWantCircleProductValues = collect($operationUserWantCircleProduct->toArray())
            ->only([
                'quantity',
                'want_priority_id',
            ])
            ->put('join_event_id', $operationUserWantCircleProduct->careAboutCircle->join_event_id)
            ->put('circle_product_id', $circleProduct->id)
            ->toArray();
        $createWantCircleProductInput = new CreateWantCircleProductInput($createWantCircleProductValues);
        (new CreateWantCircleProduct)->execute($createWantCircleProductInput);

        return $circleProduct;
    }
}
