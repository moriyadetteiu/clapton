<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use App\Exports\CircleListsExport\CircleListsExportSheet;

class CircleListsExport implements WithMultipleSheets
{
    private Collection $circleLists;
    private Collection $groupingColumns;

    public function __construct(Collection $circleLists, array $groupingColumns = [])
    {
        $this->circleLists = $circleLists;
        $this->groupingColumns = collect($groupingColumns);
    }

    public function sheets(): array
    {
        return $this
            ->aggregateCircleListsByGroupColumns()
            ->map(fn ($circleLists, $key) => new CircleListsExportSheet($circleLists, $key))
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
