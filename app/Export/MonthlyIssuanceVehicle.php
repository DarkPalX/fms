<?php

namespace App\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use App\Vehicles;
use App\IssuanceRequest;
use DB;

class MonthlyIssuanceVehicle implements FromCollection, WithMapping, WithHeadings
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

        if($this->req->type){
            $vehicles = Vehicles::where('type', $this->req->type)->get();
            $qry->whereIn('vehicle_id',$vehicles->pluck('id'));
        }   else {
            $qry->where('vehicle_id', 0);        
        }   

        if($this->req->month) {
            $qry->whereMonth('created_at', $this->req->month);
        }

        if($this->req->year) {
            $qry->whereYear('created_at', $this->req->year);
        }

        if($this->req->status && $this->req->status != 'ALL') {
            $qry->where('status', $this->req->status);
        }

        $rs = $qry->orderBy('created_at','DESC')->get();

        return $rs;

    }

    public function map($issuance) : array {

        if($this->req->status == 'ISSUED' || $this->req->status == 'ALL'){
            return [
                $issuance->uniq_code,
                $issuance->plate_no,
                $issuance->vehicle,
                $issuance->dept,
                $issuance->rq,
                $issuance->rq_costcode,
                $issuance->cost_code,
                $issuance->requested_qty,
                $issuance->actual_qty,
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
                $issuance->plate_no,
                $issuance->vehicle,
                $issuance->dept,
                $issuance->rq,
                $issuance->rq_costcode,
                $issuance->cost_code,
                $issuance->requested_qty,
                $issuance->driver,
                $issuance->status,
                $issuance->added_by,
                $issuance->created_at
            ];
        }

        if($this->req->status == 'CANCELLED'){
            return [
                $issuance->uniq_code,
                $issuance->plate_no,
                $issuance->vehicle,
                $issuance->dept,
                $issuance->rq,
                $issuance->rq_costcode,
                $issuance->cost_code,
                $issuance->requested_qty,
                $issuance->driver,
                $issuance->status,
                $issuance->initiator,
                $issuance->initiated_at,
                $issuance->remarks
            ];
        }

    }

    public function headings() : array
    {
        if($this->req->status == 'ISSUED' || $this->req->status == 'ALL'){
            return [
                'Transaction No.',
                'Plate Number',
                'Vehicle',
                'Originating Department',
                'RQ Number',
                'Originator Cost Code',
                'Expense Cost Code',
                'Requested Qty',
                'Actual Qty',
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
                'Plate Number',
                'Vehicle',
                'Originating Department',
                'RQ Number',
                'Originator Cost Code',
                'Expense Cost Code',
                'Requested Qty',
                'Driver',
                'Status',
                'Processed By',
                'Processed At'
            ];
        }

        if($this->req->status == 'CANCELLED'){
            return [
                'Transaction No.',
                'Plate Number',
                'Vehicle',
                'Originating Department',
                'RQ Number',
                'Originator Cost Code',
                'Expense Cost Code',
                'Requested Qty',
                'Driver',
                'Status',
                'Cancelled By',
                'Cancelled At',
                'Remarks'
            ];
        }

    }
}
