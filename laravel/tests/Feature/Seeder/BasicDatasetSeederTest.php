<?php

namespace Tests\Feature\Database\Seeder;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Artisan;

use Tests\TestCase;

class BasicDatasetSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRun()
    {
        $result = Artisan::call('db:seed', ['--class' => 'BasicDatasetSeeder']);
        $this->assertEquals(0, $result);
    }
}
