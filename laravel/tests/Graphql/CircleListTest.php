<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;

use Database\DatasetFactories\CircleDatasetFactory;

class CircleListTest extends TestCase
{
    use WithFaker;

    // TODO: テストの内容を強化する（現状最低限のサークル情報のみをテスト対象としている）
    public function testJoinEventCircleLists()
    {
        (new CircleDatasetFactory())->create(); // 取得対象外のデータも生成しておく

        $dataset = (new CircleDatasetFactory())->create();
        $joinEvent = $dataset['joinEvent'];

        $response = $this
            ->actingAsUser()
            ->graphQL('
                query joinEventCircleLists($joinEventId: ID!) {
                    joinEventCircleLists(join_event_id: $joinEventId) {
                        circle_id
                        circle_name
                        circle {
                            name
                        }
                    }
                }
            ', [
                'joinEventId' => $joinEvent->id,
            ])
            ->assertStatus(200);

        $expectedCircles = $dataset['circle']->pluck('name');
        $circleLists = collect($response->json('data.joinEventCircleLists'));

        $this->assertEquals(count($dataset['circle']), $circleLists->count());
        $this->assertEqualsCanonicalizing($expectedCircles, $circleLists->pluck('circle_name'));
        $this->assertEqualsCanonicalizing($expectedCircles, $circleLists->pluck('circle')->flatten());
    }

    // TODO: テストの内容を強化する（現状最低限のサークル情報のみをテスト対象としている）
    public function testTeamCircleLists()
    {
        (new CircleDatasetFactory())->create(); // 取得対象外のデータも生成しておく

        $dataset = (new CircleDatasetFactory())->create();
        $team = $dataset['team'];
        $event = $dataset['event'];

        $response = $this
            ->actingAsUser()
            ->graphQL('
                query teamCircleLists($teamId: ID!, $eventId: ID!) {
                    teamCircleLists(team_id: $teamId, event_id: $eventId) {
                        circle_id
                        circle_name
                        circle {
                            name
                        }
                    }
                }
            ', [
                'teamId' => $team->id,
                'eventId' => $event->id,
            ])
            ->assertStatus(200);

        $expectedCircles = $dataset['circle']->pluck('name');
        $circleLists = collect($response->json('data.teamCircleLists'));

        $this->assertEquals(count($dataset['circle']), $circleLists->count());
        $this->assertEqualsCanonicalizing($expectedCircles, $circleLists->pluck('circle_name'));
        $this->assertEqualsCanonicalizing($expectedCircles, $circleLists->pluck('circle')->flatten());
    }
}
