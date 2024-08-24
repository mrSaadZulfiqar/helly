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
            $table->float('commission_percentage')->default(0.00)->nullable();
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
            if (Schema::hasColumn('commission_percentage')) {
                $table->dropColumn('commission_percentage');
            }
        });
    }
};
