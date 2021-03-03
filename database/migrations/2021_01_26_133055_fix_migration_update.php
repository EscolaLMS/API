<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixMigrationUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('curriculum_sections', 'image_path')) {
            Schema::table('curriculum_sections', function (Blueprint $table) {
                $table->string('image_path', 255)->nullable();
            });
        }

        if (!Schema::hasColumn('curriculum_lectures_quiz', 'image_path')) {
            Schema::table('curriculum_lectures_quiz', function (Blueprint $table) {
                $table->string('image_path', 255)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
