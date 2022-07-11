<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Exporter;

class FileManager implements ExportFileManagerInterface
{
    private const STORAGE_DIRECTORY_NAME = 'generated';

    private Exporter $excel;

    public function __construct(Exporter $excel)
    {
        $this->excel = $excel;
    }

    public function storeFixedInterval($exporter): string
    {
        $uuid = Str::uuid();
        $filename = "{$uuid}.xlsx";
        $this->excel->store($exporter, self::STORAGE_DIRECTORY_NAME . "/{$filename}");

        return $filename;
    }

    public function downloadStoredFile(string $fileName)
    {
        $formattedNowDatetime = Carbon::now()->format("Ymd_His");
        $responseFileName = "サークルリスト_{$formattedNowDatetime}.xlsx";

        return response()->download(
            storage_path('app/' . self::STORAGE_DIRECTORY_NAME . "/{$fileName}"),
            $responseFileName
        )->deleteFileAfterSend(true);
    }
}
