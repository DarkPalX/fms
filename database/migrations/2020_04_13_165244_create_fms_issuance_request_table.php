<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFmsIssuanceRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issuance_request_tbl', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uniq_code',200);
            $table->string('rq',100);
            $table->string('batch_no',100);
            $table->string('cost_code',250);
            $table->decimal('requested_qty',16,2);
            $table->decimal('actual_qty',16,2)->nullable();
            $table->integer('dept_id');
            $table->string('dept',250);
            $table->integer('driver_id');
            $table->string('driver',250);
            $table->integer('vehicle_id');
            $table->string('plate_no',100);
            $table->string('vehicle',250);
            $table->integer('odo');
            $table->string('fuel_type',250);
            $table->string('item_code',250);
            $table->string('status',50);
            $table->text('purpose');
            $table->integer('is_outside');
            $table->string('processed_by',50)->nullable();
            $table->string('added_by',50);
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
        Schema::dropIfExists('issuance_request_tbl');
    }
}
