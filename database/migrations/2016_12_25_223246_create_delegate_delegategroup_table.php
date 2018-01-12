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

class CreateDelegateDelegategroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delegate_delegategroup', function (Blueprint $table) {
            $table->integer('delegategroup_id')->unsigned();
            $table->integer('delegate_id')->unsigned();

            $table->foreign('delegategroup_id')->references('id')->on('delegategroups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('delegate_id')->references('reg_id')->on('delegate_info')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['delegategroup_id', 'delegate_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delegate_delegategroup');
    }
}
