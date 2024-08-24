<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('security_deposit_refunds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('booking_id')->nullable();
            $table->string('refund_type')->nullable();
            $table->float('partial_amount')->default(0.00)->nullable();
            $table->integer('status')->default(0)->nullable()->comment('0=pending, 1=aggree, raise dispute');
            $table->integer('refund_status')->default(0)->nullable()->comment('0=pending, 1=refunded');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('security_deposit_refunds');
    }
};
