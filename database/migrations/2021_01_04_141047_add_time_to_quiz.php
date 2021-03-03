<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeToQuiz extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('curriculum_lectures_quiz', function (Blueprint $table) {
            $table->string('duration', 8)->nullable(); // "MM:SS"
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('curriculum_lectures_quiz', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
}
