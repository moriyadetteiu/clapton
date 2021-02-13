<?php

namespace App\Http\Controllers\Api\Develop;

use App\Http\Controllers\Controller;
use App\Support\Validation\ValidationExtractorInterface;

class ExportValidationController extends Controller
{
    private ValidationExtractorInterface $extractor;

    public function __construct(ValidationExtractorInterface $extractor)
    {
        $this->extractor = $extractor;
    }

    public function export()
    {
        return $this->extractor->extract();
    }
}
