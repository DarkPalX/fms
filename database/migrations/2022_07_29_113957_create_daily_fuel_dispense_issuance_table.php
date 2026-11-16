<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyFuelDispenseIssuanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_fuel_dispense_issuances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('issuance_id');
            $table->integer('daily_fuel_dispense_id');
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
        Schema::dropIfExists('daily_fuel_dispense_issuances');
    }
}
