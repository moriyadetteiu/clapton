<?php

namespace App\Observers;

use App\Models\CirclePlacement;

class CirclePlacementObserver
{
    public function saving(CirclePlacement $circlePlacement)
    {
        $circlePlacement->line = mb_convert_kana($circlePlacement->line, 'aKV');
    }
}
