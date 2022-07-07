<?php

namespace App\UseCase\CareAboutCircle;

use App\Models\CareAboutCircle;
use App\UseCase\UseCase;

class CreateCareAboutCircle extends UseCase
{
    public function execute(CreateCareAboutCircleInput $input)
    {
        $careAboutCircle = $this->findOrCreateCareAboutCircle($input);
        return $careAboutCircle;
    }

    private function findOrCreateCareAboutCircle(CreateCareAboutCircleInput $input)
    {
        $careAboutCircle = CareAboutCircle::where('join_event_id', $input->get('join_event_id'))
            ->where('circle_placement_id', $input->get('circle_placement_id'))
            ->first();

        if ($careAboutCircle) {
            return $careAboutCircle;
        }

        return CareAboutCircle::create($input->toArray());
    }
}
