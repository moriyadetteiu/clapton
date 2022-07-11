<?php

namespace App\UseCase\Circle;

use Illuminate\Support\Facades\DB;

use App\Models\Circle;
use App\Models\CirclePlacement;
use App\Models\EventDate;
use App\UseCase\UseCase;
use App\UseCase\UpdateDeniedException;

class UpdateCircleParticipatingInEvent extends UseCase
{
    public function execute(UpdateCircleParticipatingInEventInput $input)
    {
        $circle = DB::transaction(function () use ($input) {
            $circle = Circle::findOrFail($input->getCircleId());

            $circleData = $input->getCircleData();
            $placementData = $input->getPlacementData();
            $eventDate = EventDate::findOrFail($placementData['event_date_id']);
            $circlePlacement = $circle->circlePlacements()->inEvent($eventDate->event_id)->firstOrFail();

            // note: 他ユーザも関連している場合にサークル名の変更があった場合は、チェック済みのサークルが意図せずして変更される可能性があるため、更新を拒否する
            $isNameChanged = $circle->name !== $circleData['name'];
            $isExistsCareAboutCircleWhereHasOtherUser = $this->isExistsCareAboutCircleWhereHasOtherUser($circlePlacement);
            $isExistsFavoriteWhereHasOtherUser = $this->isExistsFavoriteWhereHasOtherUser($input->get('operation_user_id'), $circle);
            $isExistsRelatedUser = $isExistsCareAboutCircleWhereHasOtherUser || $isExistsFavoriteWhereHasOtherUser;
            if ($isNameChanged && $isExistsRelatedUser) {
                throw new UpdateDeniedException("他メンバーもこのサークルをチェックしているため、サークル名の変更はできません。\nサークルを削除後に新規登録をお願いします。");
            }

            $circle->update($circleData);
            $circlePlacement->update($placementData);

            $circle->refresh();
            $circlePlacement->refresh();

            return $circlePlacement;
        });

        return $circle;
    }

    private function isExistsCareAboutCircleWhereHasOtherUser(CirclePlacement $circlePlacement): bool
    {
        return $circlePlacement->careAboutCircles()->count() > 1;
    }

    private function isExistsFavoriteWhereHasOtherUser(string $operationUserId, Circle $circle): bool
    {
        return $circle->favorites()->where('user_id', '!=', $operationUserId)->exists();
    }
}
