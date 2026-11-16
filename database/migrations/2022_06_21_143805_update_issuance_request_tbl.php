<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIssuanceRequestTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('issuance_request_tbl', function (Blueprint $table) {
            $table->integer('tank_id')->nullable();
            $table->timestamp('issued_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('issuance_request_tbl', function (Blueprint $table) {
            $table->dropColumn(['tank_id', 'issued_date']);
        });
    }
}
