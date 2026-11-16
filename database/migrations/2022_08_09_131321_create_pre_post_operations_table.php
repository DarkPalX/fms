<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrePostOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_post_operations', function (Blueprint $table) {
            $table->increments('id');
            $table->text('remarks')->nullable();
            $table->string('conducted_by')->nullable();
            $table->string('assisted_by')->nullable();
            $table->string('witnessed_by')->nullable();
            $table->string('received_by')->nullable();
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
        Schema::dropIfExists('pre_post_operations');
    }
}
