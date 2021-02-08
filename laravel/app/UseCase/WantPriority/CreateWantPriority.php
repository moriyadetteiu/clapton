<?php

namespace App\UseCase\WantPriority;

use App\Models\WantPriority;
use App\UseCase\UseCase;

class CreateWantPriority extends UseCase
{
    public function execute(CreateWantPriorityInput $input)
    {
        $wantPriorityData = $input->toArray();
        $wantPriority = WantPriority::create($wantPriorityData);
        return $wantPriority;
    }
}
