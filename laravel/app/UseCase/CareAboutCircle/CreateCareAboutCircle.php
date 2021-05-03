<?php

namespace App\UseCase\CareAboutCircle;

use App\Models\CareAboutCircle;
use App\UseCase\UseCase;

class CreateCareAboutCircle extends UseCase
{
    public function execute(CreateCareAboutCircleInput $input)
    {
        $careAboutCircle = CareAboutCircle::create($input->toArray());
        return $careAboutCircle;
    }
}
