<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranscationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transcations', function (Blueprint $table) {
            $table->id();
            $table->string('transcation_id')->nullable();
            $table->bigInteger('booking_id')->nullable();
            $table->integer('transcation_type')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('vendor_id')->nullable();
            $table->integer('payment_status')->default(0);
            $table->string('payment_method')->nullable();
            $table->float('grand_total')->nullable();
            $table->string('gateway_type')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('currency_symbol_position')->nullable();
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
        Schema::dropIfExists('transcations');
    }
}
