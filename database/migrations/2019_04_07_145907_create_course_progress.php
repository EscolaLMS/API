<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseProgress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_progress', function (Blueprint $table) {
            $table->increments('progress_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('lecture_id');
            $table->tinyInteger('status')->default(0)->comment('0-incomplete,1-complete');
            $table->dateTime('finished_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('lecture_id')->references('lecture_quiz_id')->on('curriculum_lectures_quiz')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_progress');
    }
}
