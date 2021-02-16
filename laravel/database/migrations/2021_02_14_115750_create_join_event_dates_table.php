<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJoinEventDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('join_event_dates', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table
                ->foreignUuid('join_event_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table
                ->foreignUuid('event_date_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->boolean('is_join');
            $table->integer('number_of_tickets')->default(0);
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
        Schema::dropIfExists('join_event_dates');
    }
}
