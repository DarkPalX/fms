<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFmsDeptBudgetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fms_dept_budget_tbl', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('dept');
            $table->decimal('budget',16,2);
            $table->string('description');
            $table->date('budget_dt');
            $table->string('added_by');
            $table->datetime('added_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fms_dept_budget_tbl');
    }
}
