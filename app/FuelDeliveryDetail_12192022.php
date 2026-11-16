<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FuelDeliveryDetail extends Model
{
    public $table = 'fuel_delivery_details';

    public $guarded = [];


    public function fuel_delivery() {
        return $this->belongsTo(FuelDelivery::class, 'delivery_id');
    }


}
