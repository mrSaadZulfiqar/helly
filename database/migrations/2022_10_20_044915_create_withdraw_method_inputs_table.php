<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawMethodInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_method_inputs', function (Blueprint $table) {
            $table->id();
            $table->integer('language_id')->nullable();
            $table->bigInteger('withdraw_payment_method_id')->nullable();
            $table->tinyInteger('type')->nullable()->comment('1-text, 2-select, 3-checkbox, 4-textarea, 5-datepicker, 6-timepicker, 7-number');
            $table->string('label')->nullable();
            $table->string('name')->nullable();
            $table->string('placeholder')->nullable();
            $table->tinyInteger('required')->default(0)->comment('1-required, 0- optional');
            $table->integer('order_number')->default(0);
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
        Schema::dropIfExists('withdraw_method_inputs');
    }
}
