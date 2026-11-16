<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalRequest extends Model
{
    public $table = 'approval_request_tbl';

    protected $fillable = [ 'transaction_id', 'status', 'reason', 'request_type', 'initiator', 'initiated_at', 'approver', 'approved_at', 'remarks'];
    
    public $timestamps = false;

    public function details()
    {
        if($this->request_type == 'rq'){

            return $this->belongsTo('App\RQ','transaction_id');

        } else {

            return $this->belongsTo('App\IssuanceRequest','transaction_id');
            
        }
       
    }

    public static function countPendingApproval()
    {
        $total = ApprovalRequest::where('status','PENDING')->count();

        return $total;
    }

    public static function total_unpost_request(){
        
        $total = ApprovalRequest::whereBetween('initiated_at',[now()->startOfMonth(),now()->endOfMonth()])->count();

        return $total;
    }

    public static function check_request($id)
    {
        $qry =  ApprovalRequest::where('transaction_id',$id)->where('status','PENDING')->count();

        return $qry;
    }
}
