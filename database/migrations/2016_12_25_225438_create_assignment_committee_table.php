<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentCommitteeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
                Schema::create('assignment_committee', function (Blueprint $table) {
            $table->integer('assignment_id')->unsigned();
            $table->integer('committee_id')->unsigned();

            $table->foreign('assignment_id')->references('id')->on('assignments')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('committee_id')->references('id')->on('committee')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['assignment_id', 'committee_id']);
 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignment_committee');
    }
}
