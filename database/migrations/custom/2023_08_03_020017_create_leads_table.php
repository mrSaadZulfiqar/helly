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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->integer('status')->default(0);
			$table->double('amount', 8, 2)->nullable()->default(0.00);
			$table->timestamp('email_verified_at')->nullable();
			$table->string('facebook')->nullable();
			$table->string('twitter')->nullable();
			$table->string('linkedin')->nullable();
			$table->tinyInteger('self_pickup_status')->default(1);
			$table->tinyInteger('two_way_delivery_status')->default(1);
			$table->float('avg_rating', 8, 2)->default(0.00);
			$table->tinyInteger('show_email_addresss')->default(1);
			$table->tinyInteger('show_phone_number')->default(1);
			$table->tinyInteger('show_contact_form')->default(1);
			
			$table->bigInteger('language_id')->nullable();
            $table->string('name')->nullable();
            $table->string('shop_name')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->longText('address')->nullable();
            $table->longText('details')->nullable();
            
            $table->longText('additional_contact')->default('[]');
            $table->integer('converted_to_vendor')->default(0);
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
        Schema::dropIfExists('leads');
    }
};
