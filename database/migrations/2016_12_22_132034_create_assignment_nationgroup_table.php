<?php
/**
* Copyright (C) MUNPANEL
* This file is part of MUNPANEL System.
*
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*
* Developed by Adam Yi <xuan@yiad.am>
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentNationgroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_nationgroup', function (Blueprint $table) {
            $table->integer('assignment_id')->unsigned();
            $table->integer('nationgroup_id')->unsigned();

            $table->foreign('assignment_id')->references('id')->on('assignments')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('nationgroup_id')->references('id')->on('nationgroups')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['assignment_id', 'nationgroup_id']);
 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignment_nationgroup');
    }
}
