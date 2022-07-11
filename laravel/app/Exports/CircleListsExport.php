<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use App\Exports\CircleListsExport\CircleListsExportSheet;

class CircleListsExport implements WithMultipleSheets
{
    private Collection $circleLists;
    private Collection $groupingColumns;
    private Collection $exportColumns;

    public function __construct(Collection $circleLists)
    {
        $this->circleLists = $circleLists;
        $this->groupingColumns = collect([]);
        $this->exportColumns = collect([]);
    }

    public function groupingColumns(array $groupingColumns)
    {
        $this->groupingColumns = collect($groupingColumns);
        return $this;
    }

    public function exportColumns(array $exportColumns)
    {
        $this->exportColumns = collect($exportColumns);
        return $this;
    }

    public function sheets(): array
    {
        return $this
            ->aggregateCircleListsByGroupColumns()
            ->map(
                fn ($circleLists, $sheetName) => (new CircleListsExportSheet($circleLists, $sheetName))
                    ->exportColumns($this->exportColumns)
            )
            ->values()
            ->toArray();
    }

    private function aggregateCircleListsByGroupColumns(): Collection
    {
        if ($this->groupingColumns->isEmpty()) {
            return collect([
                'サークルリスト' => $this->circleLists,
            ]);
        }
        return $this
            ->circleLists
            ->groupBy(
                fn ($circleList) =>
                $this
                    ->groupingColumns
                    ->map(
                        fn ($groupingColumn) => $circleList->$groupingColumn
                    )
                    ->implode('_')
            );
    }
}
