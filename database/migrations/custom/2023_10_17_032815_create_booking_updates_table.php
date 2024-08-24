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
        Schema::create('booking_updates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('booking_id');
            $table->string('status');
            $table->string('status_type');
            $table->string('update_by_user_id');
            $table->string('user_type');
            $table->longText('update_details')->nullable();
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
        Schema::dropIfExists('booking_updates');
    }
};
