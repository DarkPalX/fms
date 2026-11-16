<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fuels extends Model
{
    public $table = 'fms_fuel_tbl';

    protected $fillable = [ 'fuel_type', 'isActive', 'stock_code', 'cost'];
    
    public $timestamps = false;

    public function costs()
    {
        return $this->hasMany('App\FuelHistory','fuel_id');
    }
}
