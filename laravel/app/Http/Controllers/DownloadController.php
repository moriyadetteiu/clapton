<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Exports\ExportFileManagerInterface;

class DownloadController extends Controller
{
    private ExportFileManagerInterface $fileManager;

    public function __construct(ExportFileManagerInterface $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    public function download(string $filename)
    {
        return $this->fileManager->downloadStoredFile($filename);
    }
}
