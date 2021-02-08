<?php

namespace App\UseCase\WantPriority;

use App\Models\WantPriority;
use App\UseCase\UseCase;

class UpdateWantPriority extends UseCase
{
    public function execute(UpdateWantPriorityInput $input)
    {
        $wantPriorityData = $input->toArray();
        $wantPriority = WantPriority::findOrFail($wantPriorityData['id']);
        $wantPriority->update($wantPriorityData);
        $wantPriority->refresh();
        return $wantPriority;
    }
}
