<?php

namespace App\Support\Validation;

use Illuminate\Support\Collection;

interface ValidationExtractorInterface
{
    public function extract(): Collection;
}
