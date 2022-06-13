<?php

namespace Tests\Graphql;

use Excel;

use Illuminate\Foundation\Testing\WithFaker;

use App\Models\ViewCircleList;
use Database\DatasetFactories\CircleDatasetFactory;

class MakeCircleListsExcelTest extends TestCase
{
    use WithFaker;

    public function testMakeCircleListsExcel()
    {
        $dataset = (new CircleDatasetFactory())->create();
        $circleLists = ViewCircleList::whereIn('circle_id', $dataset['circle']->pluck('id'))->get();
        Excel::fake();

        $response = $this
            ->actingAsUser($dataset['user'])
            ->graphQL(
                '
                    mutation makeCircleListsExcel($circleListIds: [ID!]!, $exportColumns: [String], $groupingColumns: [String]) {
                        makeCircleListsExcel(circle_list_ids: $circleListIds, export_columns: $exportColumns, grouping_columns: $groupingColumns) {
                            file_name
                        }
                    }
                ',
                [
                    'circleListIds' => $circleLists->pluck('id'),
                    'exportColumns' => ['event_date', 'circle_name', 'user_name', 'circle_product_name'],
                    'groupingColumns' => ['event_date'],
                ]
            )
            ->assertStatus(200);

        $result = $response->json('data.makeCircleListsExcel');
        $this->assertIsString($result['file_name']);

        Excel::assertStored("generated/{$result['file_name']}", function ($export) {
            return true;
        });
    }
}
