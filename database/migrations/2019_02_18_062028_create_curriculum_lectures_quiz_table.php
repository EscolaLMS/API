<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCurriculumLecturesQuizTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curriculum_lectures_quiz', function (Blueprint $table) {
            $table->increments('lecture_quiz_id');
            $table->unsignedInteger('section_id')->nullable();
            $table->integer('type')->nullable();
            $table->string('title', 100)->nullable();
            $table->string('image_path', 255)->nullable();
            $table->text('description')->nullable();
            $table->text('contenttext')->nullable();
            $table->integer('media')->nullable();
            $table->integer('media_type')->nullable();
            $table->integer('sort_order')->nullable();
            $table->integer('publish')->default(0);
            $table->json('resources')->nullable();
            $table->timestamps();

            $table->foreign('section_id')->references('section_id')->on('curriculum_sections')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('curriculum_lectures_quiz');
    }
}
