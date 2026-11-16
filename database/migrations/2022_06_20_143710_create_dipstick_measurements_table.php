<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDipstickMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dipstick_measurements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fuel_type');
            $table->double('qty_delivered', 8, 2)->nullable();
            $table->string('supplier')->nullable();
            $table->timestamp('date_delivered')->nullable();
            $table->string('transaction_type');
            $table->string('transaction_from');
            $table->integer('transaction_ref');
            $table->string('tank_name');
            $table->integer('dip_no');
            $table->double('cm', 8, 2);
            $table->string('created_by');            
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
        Schema::dropIfExists('dipstick_measurements');
    }
}
