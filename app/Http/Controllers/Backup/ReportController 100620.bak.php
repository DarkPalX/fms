<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

use App\IssuanceRequest;
use App\ApprovalRequest;

class ReportController extends Controller
{

    public function status()
    {
        $qry = IssuanceRequest::whereNotNull('id');

        if(isset($_GET['status'])){
            $qry->where('status',$_GET['status']);
        } else {
            $qry->where('status','PENDING');
        }

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry->whereBetween('created_at',[$_GET['startdate'].' 00:00:00',$_GET['enddate'].' 23:59:59']);
        }

        $rs = $qry->orderBy('created_at','asc')->get();


        return view('reports.issuance-status',compact('rs'));
    }

    /*
    public function department()
    {
        $qry = IssuanceRequest::whereNotNull('id');

        if(isset($_GET['dept'])){
            $qry->where('dept',$_GET['dept'])->where('status',$_GET['status']);
        } else {
            $qry->where('status','ISSUED')->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()]); 
        }

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry->whereBetween('created_at',[$_GET['startdate'].' 00:00:00',$_GET['enddate'].' 23:59:59']);
        } else {
            $qry->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()]); 
        }


        $rs = $qry->orderBy('created_at','asc')->get();
        $depts = IssuanceRequest::distinct()->orderBy('dept','asc')->get(['dept']);

        return view('reports.issuance-department',compact('depts','rs'));
    }
    */

    public function department()
    {
        //$qry = IssuanceRequest::whereNotNull('id');
        $table='';
        $depts = IssuanceRequest::distinct()->orderBy('dept','asc')->get(['dept']);
        if(isset($_GET['startdate'])){
            $startTime = strtotime( $_GET['startdate'] );
            $endTime = strtotime( $_GET['enddate'] );

            
            $hd = '';
            $dt = '';
            
            foreach($depts as $dept){
                $hd = '<tr><td>Dept</td>';
                $dt .= '<tr><td><a target="_blank" title="View Vehicles" href="
                '.route('report.issuance-department-details').'?dept='.$dept->dept.'&startdate='.$_GET['startdate'].'&enddate='.$_GET['enddate'].'&tayp='.$_GET['tayp'].'">
                '.$dept->dept.'</a></td>';
                $tayp = $_GET['tayp'];

                if($tayp == 'daily'){
                    for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) {
                        $thisDate = date( 'Y-m-d', $i ); 
                        $total = IssuanceRequest::where('dept',$dept->dept)->where('status','ISSUED')->where('processed_at','>=',$thisDate.' 00:00:00')->where('processed_at','<=',$thisDate.' 23:59:59')->sum('actual_qty');
                        $dt .= '<td>'.number_format($total,2).'</td>';
                        $hd .= '<td>'.date( 'Y-m-d', $i ).'</td>';
                    } 
                }

                if($tayp == 'monthly'){
                    $startTime = $month = strtotime( $_GET['startdate'] );
                    $endTime = strtotime( $_GET['enddate'] );
                  
                    while($month < $endTime)
                    {
                      
                        $start_month = date('Y-m-1',$month);
                        $end_month = date('Y-m-t',$month);
                        $total_month = IssuanceRequest::where('dept',$dept->dept)->where('status','ISSUED')->where('processed_at','>=',$start_month.' 00:00:00')->where('processed_at','<=',$end_month.' 23:59:59')->sum('actual_qty');

                        $dt .= '<td>'.number_format($total_month,2).'</td>';
                        $hd .= '<td>'.date( 'M Y', $month ).'</td>';
                     
                        $month = strtotime("+1 month", $month);
                    }
                   
                }

                if($tayp == 'weekly'){
                    for ( $i = $startTime; $i <= $endTime; $i = $i + 604800 ) {
                        $start_week = date( 'Y-m-d', $i ); 
                        $end_week = date('Y-m-d',strtotime("+6 days",$i));
                        $total = IssuanceRequest::where('dept',$dept->dept)->where('status','ISSUED')->where('processed_at','>=',$start_week.' 00:00:00')->where('processed_at','<=',$end_week.' 23:59:59')->sum('actual_qty');
                        $dt .= '<td>'.number_format($total,2).'</td>';
                        $hd .= '<td>'.date('M d',strtotime($start_week)).' - '.date('M d',strtotime($end_week)).'</td>';
                    } 
                   
                }


                $dt.='</tr>';
                $hd .= '</tr>';
            }
            $table = $hd.$dt;
        }

       // $depts = IssuanceRequest::distinct()->orderBy('dept','asc')->get(['dept']);

        return view('reports.issuance-department',compact('depts','table'));
    }

    public function department_details()
    {
        //$qry = IssuanceRequest::whereNotNull('id');
        $table='';
        $depts = IssuanceRequest::distinct()->orderBy('dept','asc')->get(['dept']);
        if(isset($_GET['startdate']) && isset($_GET['dept'])){
            if($_GET['dept'] == 'ALL')
                $recs = IssuanceRequest::distinct()->orderBy('vehicle','asc')->get(['vehicle_id','plate_no','vehicle']);
            else
                $recs = IssuanceRequest::distinct()->where('dept',$_GET['dept'])->orderBy('vehicle','asc')->get(['vehicle_id','plate_no','vehicle']);

            $startTime = strtotime( $_GET['startdate'] );
            $endTime = strtotime( $_GET['enddate'] );

            
            $hd = '';
            $dt = '';
            
            foreach($recs as $rec){
                $hd = '<tr><td>Dept</td>';
                $dt .= '<tr><td><a target="_blank" title="View Vehicle Profile" href="'.route('fms.vehicle-summary',$rec->vehicle_id).'">'.$rec->vehicle.'('.$rec->plate_no.')</a></td>';
                $tayp = $_GET['tayp'];

                if($tayp == 'daily'){
                    for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) {
                        $thisDate = date( 'Y-m-d', $i ); 
                        $total = IssuanceRequest::where('vehicle_id',$rec->vehicle_id)->where('status','ISSUED')->where('processed_at','>=',$thisDate.' 00:00:00')->where('processed_at','<=',$thisDate.' 23:59:59')->sum('actual_qty');
                        $dt .= '<td>'.number_format($total,2).'</td>';
                        $hd .= '<td>'.date( 'Y-m-d', $i ).'</td>';
                    } 
                }

                if($tayp == 'monthly'){
                    $startTime = $month = strtotime( $_GET['startdate'] );
                    $endTime = strtotime( $_GET['enddate'] );
                  
                    while($month < $endTime)
                    {
                      
                        $start_month = date('Y-m-1',$month);
                        $end_month = date('Y-m-t',$month);
                        $total_month = IssuanceRequest::where('vehicle_id',$rec->vehicle_id)->where('status','ISSUED')->where('processed_at','>=',$start_month.' 00:00:00')->where('processed_at','<=',$end_month.' 23:59:59')->sum('actual_qty');

                        $dt .= '<td>'.number_format($total_month,2).'</td>';
                        $hd .= '<td>'.date( 'M Y', $month ).'</td>';
                     
                        $month = strtotime("+1 month", $month);
                    }
                   
                }

                if($tayp == 'weekly'){
                    for ( $i = $startTime; $i <= $endTime; $i = $i + 604800 ) {
                        $start_week = date( 'Y-m-d', $i ); 
                        $end_week = date('Y-m-d',strtotime("+6 days",$i));
                        $total = IssuanceRequest::where('vehicle_id',$rec->vehicle_id)->where('status','ISSUED')->where('processed_at','>=',$start_week.' 00:00:00')->where('processed_at','<=',$end_week.' 23:59:59')->sum('actual_qty');
                        $dt .= '<td>'.number_format($total,2).'</td>';
                        $hd .= '<td>'.date('M d',strtotime($start_week)).' - '.date('M d',strtotime($end_week)).'</td>';
                    } 
                   
                }


                $dt.='</tr>';
                $hd .= '</tr>';
            }
            $table = $hd.$dt;
        }

       // $depts = IssuanceRequest::distinct()->orderBy('dept','asc')->get(['dept']);

        return view('reports.issuance-department-details',compact('depts','table'));
    }


    public function vehicle()
    {
        $qry = IssuanceRequest::whereNotNull('id');

        if(isset($_GET['vehicle'])){
            $qry->where('vehicle_id',$_GET['vehicle'])->where('status',$_GET['status']);
        } else {
            $qry->where('status','ISSUED')->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()]); 
        }      

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry->whereBetween('created_at',[$_GET['startdate'].' 00:00:00',$_GET['enddate'].' 23:59:59']);
        } else {
            $qry->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()]); 
        }

        if(isset($_GET['odo_start'])){
            $qry->where('odo','>=',$_GET['odo_start']);
        }

        if(isset($_GET['odo_end'])){
            $qry->where('odo','<=',$_GET['odo_end']);
        }

        if(isset($_GET['dept'])){
            $qry->where('dept',$_GET['dept']);
        }


        $rs = $qry->orderBy('created_at','asc')->get();
        $vehicle = IssuanceRequest::distinct()->orderBy('vehicle','asc')->get(['vehicle','vehicle_id','plate_no']);

        return view('reports.issuance-vehicle',compact('vehicle','rs'));
    }

    public function issuances()
    {
        $qry = IssuanceRequest::whereNotNull('id');

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry->whereBetween('created_at',[$_GET['startdate'].' 00:00:00',$_GET['enddate'].' 23:59:59']);
        } else {
            $qry->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()]); 
        }

        $rs = $qry->orderBy('created_at','asc')->get();


        return view('reports.issuances',compact('rs'));
    }


}
