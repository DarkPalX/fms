<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuelDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('date_created');
            $table->string('vehicle');
            $table->string('supplier');
            $table->string('plate_no');
            $table->string('driver');
            $table->string('fuel_type');
            $table->string('invoice_no');
            $table->string('receipt_no');
            $table->double('deliveries', 8, 2);
            $table->text('remarks');
            $table->string('delivered_by');
            $table->string('witnessed_by');
            $table->string('sg_assigned');
            $table->string('attested_by');
            $table->string('noted_by');
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
        Schema::dropIfExists('fuel_deliveries');
    }
}
