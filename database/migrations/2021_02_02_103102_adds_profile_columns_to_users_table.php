<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddsProfileColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('path_avatar')->nullable();
            $table->integer('gender')->nullable();
            $table->integer('age')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('street')->nullable();
            $table->string('postcode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('path_avatar');
            $table->dropColumn('gender');
            $table->dropColumn('age');
            $table->dropColumn('country');
            $table->dropColumn('city');
            $table->dropColumn('street');
            $table->dropColumn('postcode');
        });
    }
}
