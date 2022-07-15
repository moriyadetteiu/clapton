<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMemoColumnCareAboutCirclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('care_about_circles', function (Blueprint $table) {
            $table->text('memo')->after('circle_placement_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('care_about_circles', function (Blueprint $table) {
            $table->dropColumn('circle_placement_id');
        });
    }
}
