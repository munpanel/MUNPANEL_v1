<?php
/**
* Copyright (C) Console iT
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

class CreateNationgroupNationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nationgroup_nation', function (Blueprint $table) {
            $table->integer('nationgroup_id')->unsigned();
            $table->integer('nation_id')->unsigned();

            $table->foreign('nationgroup_id')->references('id')->on('nationgroups')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('nation_id')->references('id')->on('nations')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['nationgroup_id', 'nation_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nationgroup_nation');
    }
}
