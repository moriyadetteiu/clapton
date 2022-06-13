<?php

namespace App\Exports\CircleListsExport;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CircleListsExportSheet implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    private const COLUMN_TITLES = [
        'event_date_name' => '日付',
        'circle_placement_classification_name' => '区分',
        'placement_full' => '配置',
        'circle_name' => 'サークル名',
        'circle_product_classification_name' => '頒布物分類',
        'circle_product_name' => '頒布物名',
        'circle_product_price' => '値段',
        'want_circle_product_quantity' => '個数',
        'want_priority_name' => '優先度',
        'user_name' => '購入者名',
        'circle_memo' => '備考',
    ];

    private Collection $circleLists;
    private Collection $aggregatedCircleLists;
    private Collection $exportColumns;
    private string $title;

    public function __construct(Collection $circleLists, string $title)
    {
        $this->title = $title;
        $this->circleLists = $circleLists;
        $this->aggregatedCircleLists = $this->aggregateSameCircleInRow($circleLists);
        $this->exportColumns = collect([]);
    }

    public function exportColumns(Collection $exportColumns)
    {
        $this->exportColumns = $exportColumns;
        return $this;
    }

    public function view(): View
    {
        return view('exports.circleLists', [
            'columns' => $this->filteredExportColumns(),
            'aggregatedCircleLists' => $this->aggregatedCircleLists,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $alphabets = range('A', 'Z');
        $lastColumnIndex = count($this->filteredExportColumns());
        $lastColumnAlphabet = $alphabets[$lastColumnIndex - 1];

        $lastRowIndex = $this->circleLists->count() + 1;

        $sheet->getStyle("A1:{$lastColumnAlphabet}{$lastRowIndex}")->applyFromArray(
            [
                'borders' =>
                [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ]
            ]
        );
    }

    public function title(): string
    {
        return $this->title;
    }

    private function aggregateSameCircleInRow(Collection $circleLists): Collection
    {
        return $circleLists->reduce(function ($carry, $item) {
            $carryLastItem = $carry->last();
            if ($carryLastItem && $carryLastItem->first()->circle_id === $item->circle_id) {
                $carryLastItem->push($item);
            } else {
                $carry->push(collect([$item]));
            }

            return $carry;
        }, collect([]));
    }

    private function filteredExportColumns(): array
    {
        return collect(self::COLUMN_TITLES)
            ->when(
                $this->exportColumns->isNotEmpty(),
                fn ($columns) => $columns->filter(
                    fn ($_, $column) => $this->exportColumns->contains($column)
                )
            )
            ->toArray();
    }
}
