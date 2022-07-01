<?php

namespace App\UseCase\Circle;

use App\Models\NotParticipationCircle;
use App\UseCase\UseCase;

class NotParticipateCircleInEvent extends UseCase
{
    public function execute(NotParticipateCircleInEventInput $input): NotParticipationCircle
    {
        $notParticipateCircleInEventData = $input->toArray();
        $notParticipateCircleInEvent = NotParticipationCircle::create($notParticipateCircleInEventData);
        return $notParticipateCircleInEvent;
    }
}
