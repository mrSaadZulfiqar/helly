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
        Schema::create('additional_contacts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendor_id');
            $table->string('email')->nullable();
            $table->string('phone_full')->nullable();
            $table->string('fax_no')->nullable();
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
        Schema::dropIfExists('additional_contacts');
    }
};
