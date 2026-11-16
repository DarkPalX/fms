<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrePostOperation extends Model
{
    public $table = 'pre_post_operations';

    public $guarded = [];


    public function dipstick_measurement() {
        return $this->hasMany(DipstickMeasurement::class);
    }


}
