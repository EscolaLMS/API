<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCourseScormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('scorm.table_names');

        Schema::table('courses', function (Blueprint $table) use ($tableNames) {
            $table->bigInteger('scorm_id')->unsigned()->nullable();
            $table->foreign('scorm_id')->references('id')->on($tableNames['scorm_table']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('scorm_id');
        });
    }
}
