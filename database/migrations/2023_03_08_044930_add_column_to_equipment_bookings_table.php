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
        Schema::table('equipment_bookings', function (Blueprint $table) {
            $table->float('security_deposit_amount')->default(0.00)->nullable();
            $table->tinyInteger('return_status')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipment_bookings', function (Blueprint $table) {
            $table->dropColumn('security_deposit_amount');
            $table->dropColumn('return_status');
        });
    }
};
