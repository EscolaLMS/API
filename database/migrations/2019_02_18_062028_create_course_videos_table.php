<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCourseVideosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_videos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('video_title')->nullable();
            $table->string('video_name', 200);
            $table->string('video_type', 250)->nullable();
            $table->string('duration', 50)->nullable();
            $table->text('image_name')->nullable();
            $table->text('video_tag')->nullable();
            $table->unsignedInteger('uploader_id')->nullable();
            $table->unsignedInteger('course_id')->nullable();
            $table->boolean('processed')->default(true);
            $table->timestamps();

            $table->foreign('uploader_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('course_videos');
    }
}
