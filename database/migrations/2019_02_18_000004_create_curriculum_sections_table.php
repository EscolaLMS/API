<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCurriculumSectionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curriculum_sections', function (Blueprint $table) {
            $table->increments('section_id');
            $table->unsignedInteger('course_id')->nullable();
            $table->string('title', 100)->nullable();
            $table->string('image_path', 255)->nullable();
            $table->integer('sort_order')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('curriculum_sections');
    }
}
