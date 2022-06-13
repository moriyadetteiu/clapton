<?php

namespace Tests\Feature\Exports;

use Excel;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\ViewCircleList;
use Database\DatasetFactories\CircleDatasetFactory;
use Tests\TestCase;
use App\Exports\CircleListsExport;

class CircleListsExportTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testExportBasic()
    {
        $dataset = (new CircleDatasetFactory())->create();
        $circleLists = ViewCircleList::whereIn('circle_id', $dataset['circle']->pluck('id'))->get();
        Excel::fake();

        // TODO: 値が出力されているかのテストも増やせたらうれしい
        //       いったん正常系のみテストしている
        $circleListExport = new CircleListsExport($circleLists);
        Excel::store($circleListExport, 'circleList.xlsx');
        Excel::assertStored('circleList.xlsx', function (CircleListsExport $export) {
            $this->assertCount(1, $export->sheets());
            return true;
        });
    }

    public function testExportCustomize()
    {
        $dataset = (new CircleDatasetFactory())->create();
        $circleLists = ViewCircleList::whereIn('circle_id', $dataset['circle']->pluck('id'))->get();

        Excel::fake();

        // TODO: 値が出力されているかのテストも増やせたらうれしい
        //       いったん正常系のみテストしている
        $circleListExport = (new CircleListsExport($circleLists))
            ->groupingColumns(['event_date_name'])
            ->exportColumns(['event_date_name', 'user_name', 'circle_name', 'unexpected_column']);
        Excel::store($circleListExport, 'circleList.xlsx');
        $expectedSheetQuantity = $circleLists->pluck('event_date_name')->unique()->count();
        Excel::assertStored('circleList.xlsx', function (CircleListsExport $export) use ($expectedSheetQuantity) {
            $this->assertCount($expectedSheetQuantity, $export->sheets());
            return true;
        });
    }
}
