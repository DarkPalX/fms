<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuelDeliveryDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_delivery_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('delivery_id');
            $table->string('compartment');
            $table->double('capacity', 8, 2);
            $table->string('tank_name');
            $table->double('delivered_in_liters', 8, 2);
            $table->double('before_cm', 8, 2);
            $table->double('before_liter', 8, 2);
            $table->double('after_cm', 8, 2);
            $table->double('after_liter', 8, 2);
            $table->double('actual_delivered_in_liters', 8, 2);
            $table->string('dome_cover');
            $table->string('discharge_valve');            
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
        Schema::dropIfExists('fuel_delivery_details');
    }
}
