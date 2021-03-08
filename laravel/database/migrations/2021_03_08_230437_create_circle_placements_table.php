<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCirclePlacementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('circle_placements', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table
                ->foreignUuid('circle_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table
                ->foreignUuid('event_date_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('hole');
            $table->string('line');
            $table->integer('number');
            $table->string('a_or_b');
            $table
                ->foreignUuid('circle_placement_classification_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
        Schema::dropIfExists('circle_placements');
    }
}
