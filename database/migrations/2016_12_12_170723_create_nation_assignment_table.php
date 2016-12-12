<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentNationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_nation', function (Blueprint $table) {
            $table->integer('committee_id')->unsigned();
			$table->string('nation');
	        $table->integer('assignment_id')->unsigned();
			$table->mediumtext('assignment_content');
            $table->timestamps()->nullable();
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
            $table->foreign(['committee_id', 'nation'])->references(['committee_id', 'name'])->on('nations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignment_nation');
    }
}