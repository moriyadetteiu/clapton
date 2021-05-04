<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Database\DatasetFactories\CircleDatasetFactory;

class BasicDatasetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new CircleDatasetFactory())->create();
    }
}
