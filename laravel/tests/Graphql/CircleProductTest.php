<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;

use App\Models\CircleProduct;
use Database\DatasetFactories\CircleDatasetFactory;

class CircleProductTest extends TestCase
{
    use WithFaker;

    public function testCreateCircleProduct()
    {
        $dataset = (new CircleDatasetFactory())->one()->create();

        $circle = $dataset['circle'];
        $circlePlacement = $circle->circlePlacements()->first();
        $circleProductDefinition = CircleProduct::factory()->definition();
        $team = $dataset['team'];
        $circleProductClassification = $team->circleProductClassifications()->inRandomOrder()->first();
        $createCircleProductInput = array_merge($circleProductDefinition, [
            'circle_placement_id' => $circlePlacement->id,
            'circle_product_classification_id' => $circleProductClassification->id,
        ]);

        $response = $this
            ->actingAsUser()
            ->graphQL('
                mutation createCircleProduct($input: CircleProductInput!) {
                    createCircleProduct(input: $input) {
                        id
                        name
                        price
                        circlePlacement {
                            id
                        }
                        circleProductClassification {
                            id
                        }
                    }
                }
            ', [
                'input' => $createCircleProductInput
            ]);

        $expectedCircleProductData = Arr::except($createCircleProductInput, ['circle_placement_id', 'circle_product_classification_id']);
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'createCircleProduct' => $expectedCircleProductData,
                ]
            ]);
        $responseData = $response->json('data.createCircleProduct');

        $this->assertDatabaseHas('circle_products', $expectedCircleProductData);
        $this->assertEquals($createCircleProductInput['circle_placement_id'], $responseData['circlePlacement']['id']);
        $this->assertEquals($createCircleProductInput['circle_product_classification_id'], $responseData['circleProductClassification']['id']);
    }

    public function testUpdateCircleProduct()
    {
        $dataset = (new CircleDatasetFactory())->one()->create();
        $circle = $dataset['circle'];
        $circlePlacement = $circle->circlePlacements()->first();
        $circleProduct = $circlePlacement->circleProducts()->first();
        $circleProductDefinition = CircleProduct::factory()->definition();
        $team = $dataset['team'];
        $circleProductClassification = $team->circleProductClassifications()->inRandomOrder()->first();
        $updateCircleProductInput = array_merge($circleProductDefinition, [
            'circle_placement_id' => $circlePlacement->id,
            'circle_product_classification_id' => $circleProductClassification->id,
        ]);

        $response = $this
            ->actingAsUser()
            ->graphQL('
                mutation updateCircleProduct($id: ID!, $input: CircleProductInput!) {
                    updateCircleProduct(id: $id, input: $input) {
                        id
                        name
                        price
                        circlePlacement {
                            id
                        }
                        circleProductClassification {
                            id
                        }
                    }
                }
            ', [
                'id' => $circleProduct->id,
                'input' => $updateCircleProductInput,
            ]);

        $expectedCircleProductData = Arr::except($updateCircleProductInput, ['circle_placement_id', 'circle_product_classification_id']);
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'updateCircleProduct' => $expectedCircleProductData,
                ]
            ]);
        $responseData = $response->json('data.updateCircleProduct');

        $this->assertDatabaseHas('circle_products', $expectedCircleProductData);
        $this->assertEquals($updateCircleProductInput['circle_placement_id'], $responseData['circlePlacement']['id']);
        $this->assertEquals($updateCircleProductInput['circle_product_classification_id'], $responseData['circleProductClassification']['id']);
    }
}
