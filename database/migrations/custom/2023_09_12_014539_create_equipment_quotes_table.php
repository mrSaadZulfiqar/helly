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
        Schema::create('equipment_quotes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('equipment_id');
			$table->bigInteger('vendor_id')->nullable();
			$table->bigInteger('customer_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company_name')->nullable();
            $table->string('project_country')->nullable();
            $table->string('project_city')->nullable();
            $table->string('project_state')->nullable();
            $table->string('project_zipcode')->nullable();
            $table->string('project_startdate')->nullable();
            $table->string('worker_count')->nullable();
            $table->longText('details')->nullable();
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
        Schema::dropIfExists('equipment_quotes');
    }
};
