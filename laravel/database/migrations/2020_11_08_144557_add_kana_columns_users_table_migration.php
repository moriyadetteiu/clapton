<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKanaColumnsUsersTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->string('name_kana', 255)->after('name');
            $table->string('handle_name', 255)->after('name_kana');
            $table->string('handle_name_kana', 255)->after('handle_name');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name_kana');
            $table->dropColumn('handle_name');
            $table->dropColumn('handle_name_kana');
        });
    }
}
