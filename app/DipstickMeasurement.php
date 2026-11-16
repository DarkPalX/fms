<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DipstickMeasurement extends Model
{
    //

    protected $guarded = [];

    protected $table = 'dipstick_measurements';


    public function pre_post_operation() {
        return $this->belongsTo(PrePostOperation::class);
    }

}
