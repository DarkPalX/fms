<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDipstickMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dipstick_measurements', function (Blueprint $table) {
            $table->boolean('before_midday')->nullable();
            $table->double('to_liter', 8, 2)->nullable();
            $table->double('totalizer_digital', 8, 2)->nullable();
            $table->double('totalizer_mechanical', 8, 2)->nullable();
            $table->integer('pre_post_operation_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dipstick_measurements', function (Blueprint $table) {
            $table->dropColumn(['before_midday', 'pre_post_operation_id', 'to_liter', 'totalizer_digital','totalizer_mechanical']);
        });
    }
}
