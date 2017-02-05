<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommitteeDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
                Schema::create('committee_document', function (Blueprint $table) {
            $table->integer('committee_id')->unsigned();
            $table->integer('document_id')->unsigned();

            $table->foreign('committee_id')->references('id')->on('committees')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('document_id')->references('id')->on('documents')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['document_id', 'committee_id']);
 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_committee');
    }
}
