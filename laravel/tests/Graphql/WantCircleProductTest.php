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
        $careAboutCircle = $circle->careAboutCircles()->first();
        $team = $dataset['team'];
        $wantPriority = $team->wantPriorities()->inRandomOrder()->first();
        $createWantCircleProductInput = array_merge($wantCircleProductDefinition, [
            'circle_product_id' => $circleProduct->id,
            'care_about_circle_id' => $careAboutCircle->id,
            'want_priority_id' => $wantPriority->id,
        ]);

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
                        careAboutCircle {
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
            'care_about_circle_id',
            'want_priority_id',
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
        $this->assertEquals($createWantCircleProductInput['care_about_circle_id'], $responseData['careAboutCircle']['id']);
        $this->assertEquals($createWantCircleProductInput['want_priority_id'], $responseData['wantPriority']['id']);
    }

    public function testUpdateWantCircleProduct()
    {
        $dataset = (new CircleDatasetFactory())->one()->create();

        $wantCircleProduct = WantCircleProduct::factory()->create();

        $circle = $dataset['circle'];
        $circleProduct = $circle->circlePlacements()->first()->circleProducts()->first();
        $wantCircleProductDefinition = WantCircleProduct::factory()->definition();
        $careAboutCircle = $circle->careAboutCircles()->first();
        $team = $dataset['team'];
        $wantPriority = $team->wantPriorities()->inRandomOrder()->first();
        $createWantCircleProductInput = array_merge($wantCircleProductDefinition, [
            'circle_product_id' => $circleProduct->id,
            'care_about_circle_id' => $careAboutCircle->id,
            'want_priority_id' => $wantPriority->id,
        ]);


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
                        careAboutCircle {
                            id
                        }
                        wantPriority {
                            id
                        }
                    }
                }
            ', [
                'id' => $wantCircleProduct->id,
                'input' => $createWantCircleProductInput
            ]);

        $expectedWantCircleProductData = Arr::except($createWantCircleProductInput, [
            'circle_product_id',
            'care_about_circle_id',
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
        $this->assertEquals($createWantCircleProductInput['circle_product_id'], $responseData['circleProduct']['id']);
        $this->assertEquals($createWantCircleProductInput['care_about_circle_id'], $responseData['careAboutCircle']['id']);
        $this->assertEquals($createWantCircleProductInput['want_priority_id'], $responseData['wantPriority']['id']);
    }
}
