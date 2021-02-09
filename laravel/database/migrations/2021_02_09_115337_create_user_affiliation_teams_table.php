<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAffiliationTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_affiliation_teams', function (Blueprint $table) {
            $table->uuid('id');
            $table
                ->foreignUuid('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table
                ->foreignUuid('team_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->boolean('is_admin')->default(false);
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
        Schema::dropIfExists('user_affiliation_teams');
    }
}
