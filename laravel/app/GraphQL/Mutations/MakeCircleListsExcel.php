<?php

namespace App\GraphQL\Mutations;

use App\Exports\ExportFileManagerInterface;
use App\Exports\CircleListsExport;
use App\Models\ViewCircleList;

class MakeCircleListsExcel
{
    private ExportFileManagerInterface $fileManager;

    public function __construct(ExportFileManagerInterface $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $ids = $args['circle_list_ids'];
        $flippedIds = array_flip($ids);
        $circleLists = ViewCircleList::find($ids)
            ->sortBy(fn ($item) => $flippedIds[$item->id])
            ->values();

        $groupingColumns = $args['grouping_columns'] ?? [];
        $exportColumns = $args['export_columns'] ?? [];

        $exporter = (new CircleListsExport($circleLists))
            ->groupingColumns($groupingColumns)
            ->exportColumns($exportColumns);
        $fileName = $this->fileManager->storeFixedInterval($exporter);

        return [
            'file_name' => $fileName,
        ];
    }
}
