<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyFuelDispense extends Model
{
    //

    protected $guarded = [];

    protected $table = 'daily_fuel_dispenses';

    public function tank() {
        return $this->belongsTo(Tank::class);
    }

    public function pre_post_operation() {
        return $this->belongsTo(PrePostOperation::class);
    }

}
