<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWetstocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wetstocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tank_id');
            $table->string('day');
            $table->decimal('opening_stock', 8, 2);
            $table->decimal('actual_delivered', 8, 2);
            $table->decimal('issuance', 8, 2);
            $table->decimal('remaining_stock', 8, 2);
            $table->decimal('ending_stock', 8, 2);
            $table->decimal('daily_variance', 8, 2);
            $table->decimal('cumulative_variance', 8, 2);
            $table->decimal('cumulative_issuance', 8, 2);
            $table->decimal('cumulative_issuance_percentage', 8, 2);
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
        Schema::dropIfExists('wetstocks');
    }
}
