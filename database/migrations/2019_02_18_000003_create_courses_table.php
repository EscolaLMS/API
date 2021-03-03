<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCoursesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('instructor_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('instruction_level_id');
            $table->string('course_title');
            $table->string('course_slug');
            $table->text('keywords')->nullable();
            $table->text('overview')->nullable();
            $table->string('course_image')->nullable();
            $table->string('thumb_image')->nullable();
            $table->integer('course_video')->unsigned()->nullable();
            $table->string('duration')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('strike_out_price', 8, 2)->nullable();

            $table->boolean('is_active');
            $table->timestamps();

            $table->foreign('instructor_id')->references('id')->on('instructors')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('courses');
    }
}
