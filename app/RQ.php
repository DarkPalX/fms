<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\IssuanceRequest;

class RQ extends Model
{
    public $table = 'fms_rq_tbl';

    protected $fillable = [ 'rq_no', 'dept', 'status', 'liters', 'costcode', 'cutoff', 'batch_no', 'created_by', 'created_at', 'updated_by', 'updated_at', 'verified_by', 'verified_at', 'attachment', 'remarks','fueltype','stockcode','cost','isEditable', 'cancelled_at', 'cancelled_by'];
    
    public $timestamps = false;

    public static function countPendingRQ()
    {
    	$total = RQ::where('status','PENDING')->count();

    	return $total;
    }

    public static function rq_costcode($rq)
    {
        $qry =  RQ::where('rq_no',$rq)->first();

        return $qry->costcode;
    }

    public static function rq_balance($rq,$ccode)
    {
        $qry  = IssuanceRequest::where('rq',$rq)->where('rq_costcode',$ccode);

        $actual   = $qry->where('status','ISSUED')->sum('actual_qty');
        $pending  = $qry->where('status','PENDING')->sum('requested_qty');


        $data =  RQ::where('rq_no',$rq)->where('costcode',$ccode)->first();

        $sum = $actual+$pending;
        
        return $data->liters-$sum;
    }
}
