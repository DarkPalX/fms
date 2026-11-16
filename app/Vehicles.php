<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Auth;
use DB;

class Vehicles extends Model
{
	protected $connection = 'sqlsrv_vms';
    public $table = 'unit';

    protected $fillable = ['name', 'plateno', 'vehicle_code', 'dept', 'type', 'isECS', 'isLowFuel'];
    public $timestamps = false;

	public static function avgKML($id){
        
        $avgKML = DB::table('v_vehicle_distance_travelled_average_consumption')->where('vehicle','=',$id)->avg('avg_fuel_consu');

        return $avgKML;
	}
	
}
