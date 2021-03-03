<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_taken', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unsigned();
            $table->unsignedInteger('course_id')->unsigned();

            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('status', 20);
            $table->string('payment_method', 30);
            $table->text('order_details')->nullable();
            $table->timestamps();
        });

        Schema::create('credits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transaction_id')->unsigned();
            $table->integer('instructor_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('course_id')->unsigned();

            $table->decimal('credit', 10, 2)->nullable();
            $table->integer('credits_for')->nullable()->comment('1-course_cost,2-course_commission');
            $table->boolean('is_admin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_taken');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('credits');
    }
}
