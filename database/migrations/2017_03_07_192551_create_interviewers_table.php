<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterviewersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interviewers', function (Blueprint $table) {
            $table->integer('reg_id')->unsigned();
            $table->integer('committee_id')->nullable()->unsigned();
            $table->primary('reg_id');
            $table->foreign('reg_id')->references('id')->on('regs')->onDelete('cascade');
            $table->foreign('committee_id')->references('id')->on('committees')->onDelete('cascade');
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
        Schema::dropIfExists('interviewers');
    }
}
