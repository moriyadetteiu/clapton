<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCirclePlacementClassifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('circle_placement_classifications', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('name');
            $table
                ->foreignUuid('team_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table
                ->integer('cost')
                ->default(0)
                ->comment('登録した際に使用する購入制限のコスト');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('circle_placement_classifications');
    }
}
