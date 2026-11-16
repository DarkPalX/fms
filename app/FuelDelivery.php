<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FuelDelivery extends Model
{
    public $table = 'fuel_deliveries';

    public $guarded = [];


    public function fuel_delivery_details() {
        return $this->hasMany(FuelDeliveryDetail::class, 'delivery_id');
    }


}
