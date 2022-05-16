<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;

use App\Models\Event;
use App\Models\EventDate;
use App\Models\JoinEvent;
use App\Models\Team;

class JoinEventTest extends TestCase
{
    use WithFaker;

    public function testCreateJoinEvent()
    {
        $team = Team::factory()->create();
        $event = Event::factory()->create();
        $eventDate = EventDate::factory()->create(['event_id' => $event->id]);

        $joinEventInput = [
            'team_id' => $team->id,
            'event_id' => $event->id,
            'join_event_dates' => [[
                'event_date_id' => $eventDate->id,
                'is_join' => $this->faker->boolean(),
            ]]
        ];

        $response = $this
            ->actingAsUser()
            ->graphQL('
            mutation createJoinEvent($input: JoinEventInput!) {
                createJoinEvent(input: $input) {
                    id
                    team {
                        id
                    }
                    event {
                        id
                    }
                    user {
                        id
                    }
                    joinEventDates {
                        id
                        is_join
                        eventDate {
                            id
                        }
                    }
                }
            }
        ', [
                'input' => $joinEventInput
            ]);

        $expectData = [
            'team' => [
                'id' => $team->id
            ],
            'event' => [
                'id' => $event->id
            ],
            'user' => [
                'id' => $this->loginUser->id
            ],
            'joinEventDates' => [
                [
                    'eventDate' => [
                        'id' => $eventDate->id,
                    ],
                    'is_join' => $joinEventInput['join_event_dates'][0]['is_join']
                ]
            ]
        ];

        // 登録の確認
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'createJoinEvent' => $expectData
                ]
            ]);
        $responseData = $response->json('data.createJoinEvent');

        $this->assertIsUuid($responseData['id']);

        $expectedJoinEventData = Arr::except($responseData, ['joinEventDates', 'team', 'event', 'user']);
        $expectedJoinEventData['team_id'] = $responseData['team']['id'];
        $expectedJoinEventData['event_id'] = $responseData['event']['id'];
        $expectedJoinEventData['user_id'] = $responseData['user']['id'];
        $this->assertDatabaseHas('join_events', $expectedJoinEventData);

        foreach ($responseData['joinEventDates'] as $joinEventDate) {
            $this->assertIsUuid($joinEventDate['id']);
            $joinEventId = $responseData['id'];
            $expectedJoinEventDate = Arr::except($joinEventDate, ['eventDate']);
            $expectedJoinEventDate['join_event_id'] = $joinEventId;
            $this->assertDatabaseHas('join_event_dates', $expectedJoinEventDate);
        }
    }

    public function testUpdateJoinEvent()
    {
        $joinEvent = JoinEvent::factory()->create();
        $eventDate = EventDate::factory()->create();

        $joinEventInput = [
            'join_event_dates' => [[
                'event_date_id' => $eventDate->id,
                'is_join' => $this->faker->boolean(),
            ]]
        ];

        $response = $this
            ->actingAsUser()
            ->graphQL('
            mutation updateJoinEvent($id: ID!, $input: JoinEventInput!) {
                updateJoinEvent(id: $id, input: $input) {
                    id
                    team {
                        id
                    }
                    event {
                        id
                    }
                    user {
                        id
                    }
                    joinEventDates {
                        id
                        is_join
                        eventDate {
                            id
                        }
                    }
                }
            }
        ', [
                'id' => $joinEvent->id,
                'input' => $joinEventInput
            ]);

        $expectData = [
            'id' => $joinEvent->id,
            'team' => [
                'id' => $joinEvent->team->id
            ],
            'event' => [
                'id' => $joinEvent->event->id
            ],
            'user' => [
                'id' => $joinEvent->user->id
            ],
            'joinEventDates' => [
                [
                    'eventDate' => [
                        'id' => $eventDate->id,
                    ],
                    'is_join' => $joinEventInput['join_event_dates'][0]['is_join']
                ]
            ]
        ];

        // 登録の確認
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'updateJoinEvent' => $expectData
                ]
            ]);
        $responseData = $response->json('data.updateJoinEvent');

        $this->assertIsUuid($responseData['id']);

        $expectedJoinEventData = Arr::except($responseData, ['joinEventDates', 'team', 'event', 'user']);
        $expectedJoinEventData['team_id'] = $responseData['team']['id'];
        $expectedJoinEventData['event_id'] = $responseData['event']['id'];
        $expectedJoinEventData['user_id'] = $responseData['user']['id'];
        $this->assertDatabaseHas('join_events', $expectedJoinEventData);

        $joinEvent->refresh();
        $this->assertCount(1, $joinEvent->joinEventDates);

        foreach ($responseData['joinEventDates'] as $joinEventDate) {
            $this->assertIsUuid($joinEventDate['id']);
            $joinEventId = $responseData['id'];
            $expectedJoinEventDate = Arr::except($joinEventDate, ['eventDate']);
            $expectedJoinEventDate['join_event_id'] = $joinEventId;
            $this->assertDatabaseHas('join_event_dates', $expectedJoinEventDate);
        }
    }

    public function testJoinEventsForCircleList()
    {
        $event = Event::factory()->create();
        $team = Team::factory()->create();
        $joinEvents = JoinEvent::factory()->count(3)->create([
            'event_id' => $event->id,
            'team_id' => $team->id,
        ]);
        $otherJoinEvents = JoinEvent::factory()->create();

        $response = $this
            ->actingAsUser()
            ->graphQL('
                query joinEvents($teamId: ID!, $eventId: ID!) {
                    joinEvents(team_id: $teamId, event_id: $eventId) {
                        id
                        team {
                            id
                        }
                    }
                }
            ', [
                'teamId' => $team->id,
                'eventId' => $event->id,
            ]);

        $expectData = $joinEvents->map(fn ($joinEvent) => [
            'id' => $joinEvent->id,
            'team' => [
                'id' => $joinEvent->team_id
            ]
        ])->toArray();
        $result = $response
            ->assertStatus(200)
            ->json('data.joinEvents');
        $this->assertEqualsCanonicalizing($expectData, $result);
        $this->assertCount($joinEvents->count(), $result);
    }
}
