<?php

namespace App;
use DB;
use Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FuelRequest extends Model
{
    public $table = 'fms_fuel_request_tbl';

    protected $fillable = [ 'rq', 'request_dt', 'vehicle', 'fuel_typ', 'cost', 'liter', 'total_cost', 'odo', 'notes', 'prepared_by', 'verified_by', 'created_at', 'updated_by', 'added_by', 'department', 'driver','total_liter', 'is_outside', 'is_issued', 'batch_no', 'uniq_code', 'dept_id', 'is_cancelled', 'request_no', 'cost_code', 'item_code', 'date_out', 'destination', 'contact_person', 'contact_number', 'purpose', 'plate_no'];
    
    public $timestamps = false;

    public function vehicle_details()
    {
        return $this->belongsTo('App\ViewVehicles', 'vehicle');
    }

    public function driver_details()
    {
        return $this->belongsTo('App\Drivers', 'driver');
    }

    // public static function vehicle($id){
    //     $data = DB::table('v_vehicle_tbl')->where('id',$id)->first();

    //     return $data->name;
    // }

    // public static function driver($id){
    //     $data = DB::table('v_drivers_tbl')->where('id',$id)->first();

    //     return $data->fName.' '.$data->mName.' '.$data->lName;
    // }






    public static function totalRequestedLiter($id){
		
        $totalRequestedLiter = FuelRequest::where('vehicle','=',$id)->sum('liter');
        
		return  $totalRequestedLiter;
    }
    
    public static function totalCost($id){
		
        $totalCost = FuelRequest::where('vehicle','=',$id)->sum('total_cost');
        
		return  $totalCost;
    }
    
    public static function totalFuelRequest(){
        if(Auth::user()->is_dept == 1){
            $totalRequest = FuelRequest::where('is_issued','=',0)->where('department','=',Auth::user()->dept)->get();
        } else {
            $totalRequest = FuelRequest::where('is_issued','=',0)->where('is_cancelled','=',0)->get();
        }
    
        return count($totalRequest);
    }

    public static function totalFuelIssuance(){
        if(Auth::user()->is_dept == 1){
            $totalRequest = FuelRequest::where('is_issued','=',1)->where('department','=',Auth::user()->dept)->get();
        } else {
            $totalRequest = FuelRequest::where('is_issued','=',1)->where('is_cancelled','=',0)->get();
        }

        return count($totalRequest);
    }

    public static function totalLitersFuelRequest(){
        if(Auth::user()->is_dept == 1){
            $totalLiters = FuelRequest::where('department','=',Auth::user()->dept)->where('is_issued','=',1)->sum('liter');
        } else {
            $totalLiters = FuelRequest::where('is_issued','=',1)->sum('liter');
        }

        return $totalLiters;
    }

    public static function totalFuelCost(){
        if(Auth::user()->is_dept == 1){
            $totalCost = FuelRequest::where('department','=',Auth::user()->dept)->where('is_issued','=',1)->sum('total_cost');
        } else {
            $totalCost = FuelRequest::where('is_issued','=',1)->sum('total_cost');
        }

        return $totalCost;
    }

    public static function totalLiters(){
        $totalLiters = FuelRequest::sum('liter');

        return $totalLiters;
    }

    public static function overallCost(){
        $overallCost = FuelRequest::sum('total_cost');

        return $overallCost;
    }

    public static function first_rq_dt($id){
        $data = FuelRequest::where('vehicle','=',$id)->first(); 

        return $data->request_dt;
    }

    public static function latest_rq_dt($id){
        $data = FuelRequest::where('vehicle','=',$id)->latest('id')->first(); 

        return $data->request_dt;
    }

    public static function start_rq_dt(){
        $data = FuelRequest::first(); 

        return $data->request_dt;
    }

    public static function last_rq_dt(){
        $data = FuelRequest::latest('id')->first(); 

        return $data->request_dt;
    }

    public static function avgCost($id){
        
        $avgCost = FuelRequest::where('vehicle','=',$id)->where('is_issued','=',1)->avg('total_cost');

        return $avgCost;
	}
	
	public static function totalKM($id){
        
        $totalKM = FuelRequest::where('vehicle','=',$id)->latest('id')->first();

        return $totalKM->odo;
    }
}
