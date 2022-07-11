<?php

namespace App\Exports;

interface ExportFileManagerInterface
{
    public function storeFixedInterval($exporter): string;
    public function downloadStoredFile(string $fileName);
}
