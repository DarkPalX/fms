<?php

namespace App\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use App\IssuanceRequest;
use DB;

class IssuanceDepartment implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $req;

    public function __construct($request)
    {
        $this->req = $request;

    }

    public function collection()
    {
        $qry = IssuanceRequest::whereNotNull('id');

        if($this->req->dept == 'default'){
            $qry->where('status','ISSUED')->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()]); 
        } else {
            $qry->where('dept',$this->req->dept)->where('status',$this->req->status); 
        }

        if(isset($this->req->startdate) && strlen($this->req->startdate)>=1){
            $qry->whereBetween('created_at',[$this->req->startdate.' 00:00:00',$this->req->enddate.' 23:59:59']);
        } else {
            $qry->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()]); 
        }

        $rs = $qry->orderBy('created_at','asc')->get();

        return $rs;

    }

    public function map($issuance) : array {
        
        if($this->req->dept == 'default'){

            return [
                    $issuance->uniq_code,
                    $issuance->dept,
                    $issuance->rq,
                    $issuance->rq_costcode,
                    $issuance->cost_code,
                    $issuance->requested_qty,
                    $issuance->actual_qty,
                    $issuance->plate_no,
                    $issuance->vehicle,
                    $issuance->driver,
                    $issuance->status,
                    $issuance->added_by,
                    $issuance->created_at,
                    $issuance->processed_by,
                    $issuance->processed_at

                ];

        } else {

            if($this->req->status == 'ISSUED'){
                return [
                    $issuance->uniq_code,
                    $issuance->dept,
                    $issuance->rq,
                    $issuance->rq_costcode,
                    $issuance->cost_code,
                    $issuance->requested_qty,
                    $issuance->actual_qty,
                    $issuance->plate_no,
                    $issuance->vehicle,
                    $issuance->driver,
                    $issuance->status,
                    $issuance->added_by,
                    $issuance->created_at,
                    $issuance->processed_by,
                    $issuance->processed_at
                ];
            }

            if($this->req->status == 'PENDING'){
                return [
                    $issuance->uniq_code,
                    $issuance->dept,
                    $issuance->rq,
                    $issuance->rq_costcode,
                    $issuance->cost_code,
                    $issuance->requested_qty,
                    $issuance->plate_no,
                    $issuance->vehicle,
                    $issuance->driver,
                    $issuance->status,
                    $issuance->added_by,
                    $issuance->created_at
                ];
            }

            if($this->req->status == 'CANCELLED'){
                return [
                    $issuance->uniq_code,
                    $issuance->dept,
                    $issuance->rq,
                    $issuance->rq_costcode,
                    $issuance->cost_code,
                    $issuance->requested_qty,
                    $issuance->plate_no,
                    $issuance->vehicle,
                    $issuance->driver,
                    $issuance->status,
                    $issuance->initiator,
                    $issuance->initiated_at,
                    $issuance->remarks
                ];
            }
        }

    }

    public function headings() : array
    {
        if($this->req->dept == 'default'){

            return [
                'Transaction No.',
                'Originating Department',
                'RQ Number',
                'Originator Cost Code',
                'Expense Cost Code',
                'Requested Qty',
                'Actual Qty',
                'Plate Number',
                'Vehicle',
                'Driver',
                'Status',
                'Processed By',
                'Processed At',
                'Issued By',
                'Issued At'
            ];

        } else {


            if($this->req->status == 'ISSUED'){
                return [
                    'Transaction No.',
                    'Originating Department',
                    'RQ Number',
                    'Originator Cost Code',
                    'Expense Cost Code',
                    'Requested Qty',
                    'Actual Qty',
                    'Plate Number',
                    'Vehicle',
                    'Driver',
                    'Status',
                    'Processed By',
                    'Processed At',
                    'Issued By',
                    'Issued At'
                ];
            }

            if($this->req->status == 'PENDING'){
                return [
                    'Transaction No.',
                    'Originating Department',
                    'RQ Number',
                    'Originator Cost Code',
                    'Expense Cost Code',
                    'Requested Qty',
                    'Plate Number',
                    'Vehicle',
                    'Driver',
                    'Status',
                    'Processed By',
                    'Processed At'
                ];
            }

            if($this->req->status == 'CANCELLED'){
                return [
                    'Transaction No.',
                    'Originating Department',
                    'RQ Number',
                    'Originator Cost Code',
                    'Expense Cost Code',
                    'Requested Qty',
                    'Plate Number',
                    'Vehicle',
                    'Driver',
                    'Status',
                    'Cancelled By',
                    'Cancelled At',
                    'Remarks'
                ];
            }
        }   
    }
}
