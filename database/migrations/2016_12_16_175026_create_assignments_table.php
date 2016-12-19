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
            $table->integer('nationgroup_id')->unsigned();
			$table->enum('subject_type', ['individual', 'nation']);
			$table->enum('handin_type', ['upload', 'text']);
			$table->string('title');
			$table->mediumText('description');
			$table->dateTime('deadline');
            $table->timestamps();
            $table->foreign('nationgroup_id')->references('id')->on('nationgroups')->onDelete('cascade');
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