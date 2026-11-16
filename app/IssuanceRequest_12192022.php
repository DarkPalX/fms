<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class IssuanceRequest extends Model
{
    public $table = 'issuance_request_tbl';

    protected $fillable = ['uniq_code', 'rq', 'cost_code', 'requested_qty', 'actual_qty', 'dept', 'driver_id', 'driver', 'vehicle_id', 'plate_no', 'vehicle', 'odo', 'fuel_type', 'item_code', 'status', 'purpose', 'is_outside', 'processed_by', 'added_by', 'created_at', 'updated_at', 'updated_by', 'processed_at', 'fuel_cost', 'type', 'rq_costcode','isEditable', 'location', 'isManual','approval_req_id', 'isLock', 'tank_id', 'issued_date'];
    
    public $timestamps = false;

    public static function date_for_listing($date) {

        return Carbon::parse($date)->diffForHumans();
    }

    public static function total_unissued_request(){
        
        $total = IssuanceRequest::where('status','PENDING')->count();

        return $total;
	}

    public static function monthly_total_issuance(){
        
        $total = IssuanceRequest::where('status','ISSUED')->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()])->count();

        return $total;
    }

    public static function total_cancelled_request(){
        
        $total = IssuanceRequest::where('status','CANCELLED')->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()])->count();

        return $total;
    }

}
