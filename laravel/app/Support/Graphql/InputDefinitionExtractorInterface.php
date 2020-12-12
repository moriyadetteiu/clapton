<?php

namespace App\Support\Graphql;

use Illuminate\Support\Collection;

interface InputDefinitionExtractorInterface
{
    public function extract(string $inputName): Collection;
}
