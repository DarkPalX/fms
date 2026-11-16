<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyFuelDispensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_fuel_dispenses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tank_id')->unsigned();
            $table->timestamp('date_created')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->text('description');
            $table->double('beginning_dipstick_measurement', 8, 2);
            $table->double('beginning_totalizer_reading', 8, 2);
            $table->double('ending_dipstick_measurement', 8, 2);
            $table->double('ending_totalizer_reading', 8, 2);            
            $table->double('total_dispensed', 8, 2);
            $table->text('remarks');
            $table->string('prepared_by');
            $table->string('received_by');
            $table->string('encoded_by');
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
        Schema::dropIfExists('daily_fuel_dispenses');
    }
}
