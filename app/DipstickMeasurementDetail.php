<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DipstickMeasurementDetail extends Model
{

    protected $guarded = [];

    protected $table = 'dipstick_measurement_details';


    public function dipstick_measurement() {
        return $this->belongsTo(DipstickMeasurement::class);
    }

}
