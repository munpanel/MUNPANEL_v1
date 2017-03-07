<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDelegategroupDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('delegategroup_document', function (Blueprint $table) {
            $table->integer('document_id')->unsigned();
            $table->integer('delegategroup_id')->unsigned();

            $table->foreign('document_id')->references('id')->on('documents')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('delegategroup_id')->references('id')->on('delegategroups')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['document_id', 'delegategroup_id']);
 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delegategroup_document');
    }
}
