<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');//订单号
            /*** TEEGON ***/
            $table->string('charge_id')->nullable();//流水号
            $table->string('buyer')->nullable();//支付方
            $table->string('payment_no')->nullable();//第三方交易单号
            /*** TEEGON ***/
            $table->integer('user_id')->unsigned();
            $table->string('content');//JSON
            $table->double('price');//冗余，空间换时间
            $table->string('payment_channel');//wxpay, alipay  为方便扩展，天工可能开发新接口如Apple Pay等，不做成enum
            $table->dateTime('payed_at')->nullable();//亦可用于判断是否已支付
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
