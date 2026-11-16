<?php

namespace App\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use App\IssuanceRequest;
use DB;

class Issuances implements FromCollection, WithMapping, WithHeadings
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
        // $rs = IssuanceRequest::whereBetween('created_at',[$this->req->startdate.' 00:00:00',$this->req->enddate.' 23:59:59'])->orderBy('created_at','asc')->get();

        // return $rs;

        $req = $this->req;

        $qry = IssuanceRequest::whereNotNull('id')->where('status', $req->status ?? 'ISSUED');

        if($req->status == 'PENDING'){
            $qry->whereBetween('created_at',[$req->startdate.' 00:00:00',$req->enddate.' 23:59:59']);
        }   
        else{
            $qry->whereBetween('processed_at',[$req->startdate.' 00:00:00',$req->enddate.' 23:59:59']);
        }

        $qry->where(function($query) use ($req) {
            if(!is_null($req->department) )
                $query->where('dept', $req->department);
        });

        $qry->where(function($query) use ($req) {
            if(!is_null($req->fuel_type))
                $query->where('fuel_type', $req->fuel_type);
        });

        $qry->where(function($query) use ($req) {
            if($req->location !="")
                $query->where('location', $req->location);
        });


        if($req->status == 'PENDING'){
            return $rs = $qry->orderBy('created_at','desc')->get();
        }   
        else{
            return $rs = $qry->orderBy('processed_at','desc')->get();
        }
    }

    public function map($issuance) : array {
        
        return [
            $issuance->uniq_code,
            $issuance->dept,
            $issuance->rq,
            $issuance->rq_costcode,
            $issuance->cost_code,
            $issuance->fuel_type,
            $issuance->item_code,
            $issuance->requested_qty,
            $issuance->actual_qty,
            $issuance->plate_no,
            $issuance->vehicle,
            $issuance->driver,
            $issuance->alternate_driver,
            $issuance->added_by,
            $issuance->created_at,
            $issuance->status,
            $issuance->processed_by,
            $issuance->processed_at,
            $issuance->initiator,
            $issuance->initiated_at,
            $issuance->remarks,
            $issuance->location,

        ];
    }

    public function headings() : array
    {
        return [
            'Transaction No.',
            'Originating Department',
            'RQ Number',
            'Originator Cost Code',
            'Expense Cost Code',
            'Fuel Type',
            'Stock Code',
            'Requested Qty',
            'Actual Qty',
            'Plate Number',
            'Vehicle',
            'Driver',
            'Alternate Driver',
            'Processed By',
            'Processed At',
            'Status',
            'Issued By',
            'Issued At',
            'Cancelled By',
            'Cancelled At',
            'Remarks',
            'Location'
        ];
    }
}
