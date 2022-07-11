<?php

namespace App\UseCase\Circle;

use App\Models\NotParticipationCircle;
use App\UseCase\UseCase;

class CancelNotParticipateCircleInEvent extends UseCase
{
    public function execute(CancelNotParticipateCircleInEventInput $input): NotParticipationCircle
    {
        $notParticipationCircle = NotParticipationCircle::where('circle_id', $input->get('circle_id'))
            ->where('event_id', $input->get('event_id'))
            ->firstOrFail();
        $notParticipationCircle->delete();
        return $notParticipationCircle;
    }
}
