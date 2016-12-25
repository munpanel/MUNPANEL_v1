<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentDelegategroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
                Schema::create('assignment_delegategroup', function (Blueprint $table) {
            $table->integer('assignment_id')->unsigned();
            $table->integer('delegategroup_id')->unsigned();

            $table->foreign('assignment_id')->references('id')->on('assignments')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('delegategroup_id')->references('id')->on('delegategroup')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['assignment_id', 'delegategroup_id']);
 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignment_delegategroup');
    }
}
