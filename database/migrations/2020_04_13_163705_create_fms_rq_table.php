<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFmsRqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fms_rq_tbl', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rq_no',100);
            $table->string('dept',200);
            $table->string('status',50);
            $table->integer('isLock');
            $table->string('batch_no',200)->nullable();
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
        Schema::dropIfExists('fms_rq_tbl');
    }
}
