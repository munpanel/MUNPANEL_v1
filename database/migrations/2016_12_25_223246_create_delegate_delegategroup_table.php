<?php

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
            $table->foreign('delegate_id')->references('user_id')->on('delegate-info')->onUpdate('cascade')->onDelete('cascade');

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
        //
    }
}
