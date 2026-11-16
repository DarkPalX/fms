<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFmsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fms_users_tbl', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100);
            $table->string('domainAccount',100);
            $table->string('password',100);
            $table->string('role',100);
            $table->string('dept',100);
            $table->integer('isActive');
            $table->string('remember_token',200);
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
        Schema::dropIfExists('fms_users_tbl');
    }
}
