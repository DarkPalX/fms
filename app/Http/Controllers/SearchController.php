<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

//Models
use App\IssuanceRequest;
use App\RQ;

class SearchController extends Controller
{

    public function __construct()
    {   
        $this->conn_vms = DB::connection('sqlsrv_vms'); // VEHICLE MONITORING SYSTEM DB CONNECTION
    }

    public function search_ref_no(Request $req){

        $output = "";

        $data = IssuanceRequest::where('status','<>','ISSUED')->where('uniq_code','LIKE', '%'.$req->ref_no.'%')
            ->orderBy('created_at','desc')
            ->get();

        $rows = count($data);

        if($rows > 0){
            foreach($data as $key => $d){

                if($d->status == 'PENDING'){
                    $btn = '<a href="'.route('process.issuance',$d->id).'" class="btn btn-xs btn-primary">Process</a>';
                    if(auth()->user()->role != 'gas man' || auth()->user()->role == 'clerk' ) {
                        $btn .= "<button type='button' onclick='unlock_request({$d->id});' class='btn btn-xs btn-primary' style='margin-left:5px;'>Unlock</button>";
                    }
                } else {
                    $btn = $d->remarks;
                }

                $output .= 
                '<tr>'.
                    '<td>'.$d->uniq_code.'</td>'.
                    '<td>'.$d->dept.'</td>'.
                    '<td>'.$d->added_by.'<br><small>'.date('Y-m-d h:i A',strtotime($d->created_at)).'</small></td>'.
                    '<td>'.$d->driver.'</td>'.
                    '<td>'.$d->vehicle.'<br><small>'.$d->plate_no.'</small></td>'.
                    '<td class="text-right">'.$d->requested_qty.'</td>'.
                    '<td>'.$d->fuel_type.'</td>'.
                    '<td>'.number_format($d->actual_qty * $d->fuel_cost,2).'</td>'.
                    '<td>'.$d->status.'</td>'.
                    '<td>'.$btn.'</td>'.
                '</tr>';
            }
            return Response($output);
        }
        else {
            $output .= '<tr><td colspan="10"><center>Transaction # not found.</center></td></tr>';
            return Response($output);
        }

    }

    public function search_issued_ref_no(Request $req){

        $output = "";

        $data = IssuanceRequest::where('status','ISSUED')->where('uniq_code','LIKE', '%'.$req->ref_no.'%')
            ->orderBy('created_at','desc')
            ->get();

        $rows = count($data);
        if($rows > 0){
            foreach($data as $key => $d){
                if(auth()->user()->dept == 'AUDIT & SYSTEMS IMPROVEMENT DEPARTMENT'){
                    $td = '<td><a href="'.route('edit-issued-request',$d->id).'" class="btn btn-xs btn-primary">Edit</a></td>';
                } else {
                    $td = '';
                }
                $output .= 
                '<tr>'.
                    '<td><a href="'.route('view.issuance',$d->id).'">'.$d->uniq_code.'</a></td>'.
                    '<td>'.$d->dept.'</td>'.
                    '<td>'.$d->vehicle.'<br><small>'.$d->plate_no.'</small></td>'.
                    '<td>'.$d->driver.'</td>'.
                    '<td>'.$d->rq.'<br><small>'.$d->rq_costcode.'</small></td>'.
                    '<td>'.$d->cost_code.'</td>'.
                    '<td class="text-right">'.number_format($d->requested_qty,2).'</td>'.
                    '<td class="text-right">'.number_format($d->actual_qty,2).'</td>'.
                    '<td class="text-right">'.number_format($d->actual_qty * $d->fuel_cost,2).'</td>'.
                    '<td>'.$d->processed_by.'<br><small>'.date('Y-m-d h:i A',strtotime($d->processed_at)).'</small></td>'.
                    '<td>'.$d->added_by.'<br><small>'.date('Y-m-d h:i A',strtotime($d->created_at)).'</small></td>';

                    if(auth()->user()->role != 'gas man') {
                        $output .= '<td>'.$d->location.'</td>';
                    }

                    if(auth()->user()->dept == 'AUDIT & SYSTEMS IMPROVEMENT DEPARTMENT' 
                                    || auth()->user()->domainAccount=='easia') {
                        $output .='<td><a href="'. route('edit-issued-request',$d->id) .'" class="btn btn-xs btn-primary">Edit</a></td></tr>';
                    } else {
                        '</tr>';                        
                    }

            }
            return Response($output);
        }
        else {
            $output .= '<tr><td colspan="12"><center>Transaction # not found.</center></td></tr>';
            return Response($output);
        }

    }

    public function search_rqno(Request $req){

        $output = "";

        $data = RQ::where('status',$req->status)->where('rq_no','LIKE', '%'.$req->rqno.'%')->orWhere('costcode','LIKE', '%'.$req->rqno.'%')->get();

        $rows = count($data);

        if($rows > 0){
            foreach($data as $key => $d){

                if($d->attachment != 'NULL'){
                    $btn = '<a href="https://mlappsvr.philsaga.com/dportal/storage/app/public/attachments/'.$d->rq_no.'/'.$d->attachment.'" class="btn btn-xs btn-secondary" data-toggle="lightbox" data-title="'.$d->rq_no.'<br><small>Liters: '.$d->liters.'</small><br><small>Cutoff: '.$d->cutoff.'</small><br><small>Costcode: '.$d->costcode.'</small><br><small>Fuel Type: '.$d->fueltype.'</small>">View Attachment</a>';
                } else {
                    $btn = '';
                }

                if($req->status == 'PENDING'){
                    $approvalBtn = '<a href="" style="margin-right:5px;" class="btn btn-xs btn-primary verify" data-toggle="modal" data-target="#modal-verify" data-rq_id="'.$d->id.'">Verify</a>'.
                        '<a href="" style="margin-right:5px;" class="btn btn-xs btn-danger unverify" data-toggle="modal" data-target="#modal-unverify" data-rq_id="'.$d->id.'">Unverify</a>';
                    $appovaltd = '';
                } else {
                    $approvalBtn = '';
                    if($req->status == 'VERIFIED'){
                        $appovaltd = '<td>'.$d->verified_by.'<br><small>'.date('Y-m-d h:i A',strtotime($d->verified_at)).'</small></td>';
                    } else {
                        $appovaltd = '<td>'.$d->verified_by.'<br><small>'.date('Y-m-d h:i A',strtotime($d->verified_at)).'</small></td><td>'.$d->remarks.'</td>';
                    }
                    
                }

                $output .= 
                '<tr>'.
                    '<td>'.$d->rq_no.'<br><small>'.$d->costcode.'</small></td>'.
                    '<td>'.number_format($d->liters,2).'</td>'.
                    '<td>'.$d->cutoff.'</td>'.
                    '<td>'.$d->fueltype.'</td>'.
                    '<td>'.$d->created_by.' - '.date('Y-m-d h:i A',strtotime($d->created_at)).'<br> <small>'.$d->dept.'</small></td>'.
                    ''.$appovaltd.''.
                    '<td>'.$approvalBtn.$btn.'</td>'.
                '</tr>';
            }
            return Response($output);
        }
        else {
            $output .= '<tr><td colspan="8" class="text-uppercase"><center>rq/costcode not found.</center></td></tr>';
            return Response($output);
        }

    }

    public function search_plateno(Request $req){
 
        $output = "";
 
        $data = $this->conn_vms->table('unit')->where('plateno','LIKE', '%'.$req->plateno.'%')->get();
 
        $rows = count($data);
 
        if($rows > 0){
            foreach($data as $key => $d){
 
            $isLowFuel = (bool)($d->isLowFuel ?? false); // Adjust to $d if $v was a typo
 
            $output .= '<tr>' .
                '<td>' . e($d->name) . '</td>' .
                '<td>' . e($d->plateno) . '</td>' .
                '<td>' . e($d->vehicle_code) . '</td>' .
                '<td>' . e($d->dept) . '</td>' .
                '<td>' . e($d->type) . '</td>' .
                '<td>';
 
            if ($isLowFuel) {
                $output .= '<a href="' . route('low_fuel.vehicle', ['vehicle_id' => $d->id, 'status' => 0]) . '"><i class="fa fa-toggle-on"></i></a> ' .
                        '<span class="text-danger">LOW FUEL ECONOMY</span>';
            } else {
                $output .= '<a href="' . route('low_fuel.vehicle', ['vehicle_id' => $d->id, 'status' => 1]) . '"><i class="fa fa-toggle-off"></i></a> ' .
                        '<span class="text-dark">GOOD FUEL ECONOMY</span>';
            }
 
            $output .= '</td>' .
                '<td><a class="btn btn-secondary btn-xs mb-1" href="' . route('fms.vehicle-summary-monthly', $d->id) . '">View Summary</a></td>' .
            '</tr>';
 
                // $output .=
                // '<tr>'.
                //     '<td>'.$d->name.'</td>'.
                //     '<td>'.$d->plateno.'</td>'.
                //     '<td>'.$d->vehicle_code.'</td>'.
                //     '<td>'.$d->dept.'</td>'.
                //     '<td>'.$d->type.'</td>'.
                //     '<td><a class="btn btn-secondary btn-xs mb-1" href="'.route('fms.vehicle-summary-monthly',$d->id).'">View Summary</a></td>'.
                // '</tr>';
            }
            return Response($output);
        }
        else {
            $output .= '<tr><td colspan="6"><center>Plate number not found.</center></td></tr>';
            return Response($output);
        }
 
    }

    

}
