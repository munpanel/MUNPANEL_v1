<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nations', function (Blueprint $table) {
			$table->increments('id');
            $table->integer('committee_id')->unsigned();
			$table->string('name');
			$table->integer('conpetence')->unsigned()->default(1);
			$table->boolean('veto_power')->default(false);
			$table->boolean('attendance')->nullable();
            $table->timestamps();
            $table->unique(['committee_id', 'name']);
            $table->foreign('committee_id')->references('id')->on('committees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nations');
    }
}
