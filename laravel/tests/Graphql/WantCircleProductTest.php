<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;

use App\Models\WantCircleProduct;
use Database\DatasetFactories\CircleDatasetFactory;

class WantCircleProductTest extends TestCase
{
    use WithFaker;

    public function testCreateWantCircleProduct()
    {
        $dataset = (new CircleDatasetFactory())->one()->create();

        $circle = $dataset['circle'];
        $circleProduct = $circle->circlePlacements()->first()->circleProducts()->first();
        $wantCircleProductDefinition = WantCircleProduct::factory()->definition();
        $team = $dataset['team'];
        $wantPriority = $team->wantPriorities()->inRandomOrder()->first();
        $createWantCircleProductInput = array_merge($wantCircleProductDefinition, [
            'circle_product_id' => $circleProduct->id,
            'user_id' => $dataset['user']->id,
            'want_priority_id' => $wantPriority->id,
        ]);
        $createWantCircleProductInput = collect($createWantCircleProductInput)
            ->except(['care_about_circle_id'])
            ->toArray();

        $response = $this
            ->actingAsUser()
            ->graphQL('
                mutation createWantCircleProduct($input: WantCircleProductInput!) {
                    createWantCircleProduct(input: $input) {
                        id
                        quantity
                        circleProduct {
                            id
                        }
                        user {
                            id
                        }
                        wantPriority {
                            id
                        }
                    }
                }
            ', [
                'input' => $createWantCircleProductInput
            ]);

        $expectedWantCircleProductData = Arr::except($createWantCircleProductInput, [
            'circle_product_id',
            'want_priority_id',
            'user_id',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'createWantCircleProduct' => $expectedWantCircleProductData,
                ]
            ]);
        $responseData = $response->json('data.createWantCircleProduct');

        $this->assertDatabaseHas('want_circle_products', $expectedWantCircleProductData);
        $this->assertEquals($createWantCircleProductInput['circle_product_id'], $responseData['circleProduct']['id']);
        $this->assertEquals($dataset['user']->id, $responseData['user']['id']);
        $this->assertEquals($createWantCircleProductInput['want_priority_id'], $responseData['wantPriority']['id']);
    }

    public function testUpdateWantCircleProduct()
    {
        $dataset = (new CircleDatasetFactory())->one()->create();

        $wantCircleProduct = WantCircleProduct::factory()->create();

        $wantCircleProductDefinition = WantCircleProduct::factory()->definition();
        $team = $dataset['team'];
        $wantPriority = $team->wantPriorities()->inRandomOrder()->first();
        $updateWantCircleProductInput = array_merge($wantCircleProductDefinition, [
            'want_priority_id' => $wantPriority->id,
        ]);
        $updateWantCircleProductInput = collect($updateWantCircleProductInput)
            ->except(['user_id', 'circle_product_id'])
            ->toArray();

        $response = $this
            ->actingAsUser()
            ->graphQL('
                mutation updateWantCircleProduct($id: ID!, $input: WantCircleProductInput!) {
                    updateWantCircleProduct(id: $id, input: $input) {
                        id
                        quantity
                        circleProduct {
                            id
                        }
                        user {
                            id
                        }
                        wantPriority {
                            id
                        }
                    }
                }
            ', [
                'id' => $wantCircleProduct->id,
                'input' => $updateWantCircleProductInput
            ]);

        $expectedWantCircleProductData = Arr::except($updateWantCircleProductInput, [
            'want_priority_id',
        ]);
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'updateWantCircleProduct' => $expectedWantCircleProductData,
                ]
            ]);
        $responseData = $response->json('data.updateWantCircleProduct');

        $this->assertDatabaseHas('want_circle_products', $expectedWantCircleProductData);
        $this->assertEquals($updateWantCircleProductInput['want_priority_id'], $responseData['wantPriority']['id']);
    }
}
