<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('conference_id')->unsigned();
            $table->integer('reg_id')->unsigned();
            $table->integer('interviewer_id')->nullabled()->unsigned();
            $table->dateTime('assigned_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->enum('status', ['assigned', 'arranged', 'cancelled', 'passed', 'failed', 'exempted']);
            $table->float('score');
            $table->text('feedback')->nullable();
            $table->timestamps();
            $table->foreign('conference_id')->references('id')->on('conferences')->onDelete('cascade');
            $table->foreign('reg_id')->references('id')->on('regs')->onDelete('cascade');
            $table->foreign('interviewer_id')->references('id')->on('regs')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interviews');
    }
}