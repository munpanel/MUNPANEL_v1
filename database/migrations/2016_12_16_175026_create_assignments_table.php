<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('conference_id')->unsigned();
            $table->enum('subject_type', ['individual', 'nation', 'partner']);
            $table->enum('handin_type', ['upload', 'text', 'form']);
            $table->boolean('reg_assignment');//->default(false);
            $table->string('title');
            $table->mediumText('description');
            $table->dateTime('deadline');
            $table->timestamps();
            $table->unique(['title', 'conference_id']);
            $table->foreign('conference_id')->references('id')->on('conferences')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignments');
    }
}
