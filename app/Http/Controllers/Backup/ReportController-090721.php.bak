<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

use App\IssuanceRequest;
use App\ApprovalRequest;
use App\Vehicles;
use App\Fuels;
class ReportController extends Controller
{

    public $months = [
        '01'    => 'January',
        '02'    => 'February',
        '03'    => 'March',
        '04'    => 'April',
        '05'    => 'May',
        '06'    => 'June',
        '07'    => 'July',
        '08'    => 'August',
        '09'    => 'September',
        '10'    => 'October',
        '11'    => 'November',
        '12'    => 'December'
    ];

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

    public function vehicles_details()
    {
        //$qry = IssuanceRequest::whereNotNull('id');
        $table='';
        
        $depts = IssuanceRequest::distinct()->orderBy('dept','asc')->get(['dept']);
        if(isset($_GET['startdate']) && isset($_GET['dept'])){
            if($_GET['dept'] == 'ALL')
                $recs = IssuanceRequest::distinct()->get(['vehicle_id']);
            else
                $recs = IssuanceRequest::distinct()->where('dept',$_GET['dept'])->get(['vehicle_id']);

            $startTime = strtotime( $_GET['startdate'] );
            $endTime = strtotime( $_GET['enddate'] );

            
            $hd = '';
            $dt = '';
            
            foreach($recs as $rec){
                $vehicle = Vehicles::whereId($rec->vehicle_id)->first();
                $hd = '<tr><td>Vehicles</td>';
                $dt .= '<tr><td><a target="_blank" title="View Vehicle Profile" href="'.route('fms.vehicle-summary',$rec->vehicle_id).'">'.$vehicle->name.'('.$vehicle->plateno.')</a></td>';
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
                    $totalcols=0;
                    $startTime = $month = strtotime( $_GET['startdate'] );
                    $endTime = strtotime( $_GET['enddate'] );
                  
                    while($month < $endTime)
                    {
                      
                        $start_month = date('Y-m-1',$month);
                        $end_month = date('Y-m-t',$month);
                        $records = IssuanceRequest::where('vehicle_id',$rec->vehicle_id)->where('status','ISSUED')->where('processed_at','>=',$start_month.' 00:00:00')->where('processed_at','<=',$end_month.' 23:59:59')->orderBy('processed_at','ASC')->get();
                        $startq = IssuanceRequest::where('vehicle_id',$rec->vehicle_id)->where('status','ISSUED')->where('processed_at','<',$start_month.' 00:00:00')->orderBy('processed_at','DESC')->first(); 
                        if(empty($startq)){
                            $start = 0;
                        }else{
                            $start = $startq->odo;
                        }
                   
                        $final = 0;
                        $total_distance = 0;
                        $fuel_issued = 0;
                        $fuel_economy = 0;
                        $fuel_consumption = 0;
                        $tots = 0;

                        if($records->count() > 0){
                            foreach($records as $record){
                                $tots++;
                                // if($tots == 1){
                                //     $start = $record->odo;
                                // }                            
                                $fuel_issued += $record->actual_qty;                            
                            }
                            $final = $record->odo;
                            $total_distance = $final - $start;

                            if($fuel_issued > 0)
                                $fuel_economy = $total_distance / $fuel_issued;
                            if($total_distance > 0)
                                $fuel_consumption = ($fuel_issued / $total_distance) * 100;

                            $dt .= '
                                <td>'.number_format($start,2).'</td>
                                <td>'.number_format($final,2).'</td>
                                <td>'.number_format($total_distance,2).'</td>
                                <td>'.number_format($fuel_issued,2).'</td>
                                <td>'.number_format($fuel_economy,2).'</td>
                                <td>'.number_format($fuel_consumption,2).'</td>
                            ';
                        }
                        else{
                            $dt .= '
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            ';
                        }

                        $hd .= '<td colspan="6" align="center">'.date( 'M Y', $month ).'</td>';
                     
                        $month = strtotime("+1 month", $month);
                        $totalcols++;
                    }
                   
                }

                if($tayp == 'weekly'){
                    $totalcols=0;
                    for ( $i = $startTime; $i <= $endTime; $i = $i + 604800 ) {
                        $start_week = date( 'Y-m-d', $i ); 
                        $end_week = date('Y-m-d',strtotime("+6 days",$i));
                        $records = IssuanceRequest::where('vehicle_id',$rec->vehicle_id)->where('status','ISSUED')->where('processed_at','>=',$start_week.' 00:00:00')->where('processed_at','<=',$end_week.' 23:59:59')->get();   
                        $startq = IssuanceRequest::where('vehicle_id',$rec->vehicle_id)->where('status','ISSUED')->where('processed_at','<=',$start_week.' 00:00:00')->orderBy('processed_at','DESC')->first(); 
                        if(empty($startq)){
                            $start = 0;
                        }else{
                            $start = $startq->odo;
                        }
                        
                        $final = 0;
                        $total_distance = 0;
                        $fuel_issued = 0;
                        $fuel_economy = 0;
                        $fuel_consumption = 0;
                        $tots = 0;

                        if($records->count() > 0){
                            foreach($records as $record){
                                $tots++;
                                // if($tots == 1){
                                //     $start = $record->odo;
                                // }                            
                                $fuel_issued += $record->actual_qty;                            
                            }
                            $final = $record->odo;
                            $total_distance = $final - $start;

                            if($fuel_issued > 0)
                                $fuel_economy = $total_distance / $fuel_issued;
                            if($total_distance > 0)
                                $fuel_consumption = ($fuel_issued / $total_distance) * 100;

                            $dt .= '
                                <td>'.number_format($start,2).'</td>
                                <td>'.number_format($final,2).'</td>
                                <td>'.number_format($total_distance,2).'</td>
                                <td>'.number_format($fuel_issued,2).'</td>
                                <td>'.number_format($fuel_economy,2).'</td>
                                <td>'.number_format($fuel_consumption,2).'</td>
                            ';
                        }
                        else{
                            $dt .= '
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            ';
                        }

                                            
                        $hd .= '<td colspan="6" align="center">'.date('M d',strtotime($start_week)).' - '.date('M d',strtotime($end_week)).'</td>';
                        $totalcols++;
                    } 
                   
                }


                $dt.='</tr>';
                $hd .= '</tr>';
            }


            $hds='
                <tr style="font-size:10px;text-align:center;">
                    <td>&nbsp;</td>
                    ';

            for($i=1; $i<=$totalcols; $i++ ){
                $hds.='                   
                    <td>Starting KM</td>
                    <td>Final KM</td>
                    <td>Total Distance (KM)</td>
                    <td>Fuel Issued (L)</td>
                    <td>Fuel Economy (km/L)</td>
                    <td>Fuel Consumption (L/100km)</td>
                ';
            }
            $hds .= '</tr>';

            $table = $hd.$hds.$dt;
        }

       // $depts = IssuanceRequest::distinct()->orderBy('dept','asc')->get(['dept']);

        return view('reports.issuance-vehicle-details',compact('depts','table'));
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


    public function vehicleMonthlyIssuance()
    {

        $qry = IssuanceRequest::whereNotNull('id');
        $months = $this->months;
        $vehicles = null;

        if(isset($_GET['v_type'])) {
            $vehicles = Vehicles::where('type', $_GET['v_type'])->get();
            $qry->whereIn('vehicle_id', $vehicles->pluck('id'));
        }   else {
            $qry->where('vehicle_id', 0);        
        }  

        if(isset($_GET['month'])) {
            $qry->whereMonth('created_at', $_GET['month']);
        }

        if(isset($_GET['year'])) {
            $qry->whereYear('created_at', $_GET['year']);
        }

        if(isset($_GET['status']) && $_GET['status'] != 'ALL') {
            $qry->where('status', $_GET['status']);
        }

        $rs = $qry->orderBy('created_at','DESC')->get();

        return view('reports.monthly-issuance-vehicle',compact('rs','months'));
    }


    public function issuedIssuances(Request $request)
    {   

        $qry = IssuanceRequest::whereNotNull('id')->where('status', 'ISSUED');
        $depts = IssuanceRequest::distinct()->orderBy('dept','asc')->get(['dept']);
        $fuel_types = Fuels::all()->pluck('fuel_type');
        $vehicles = IssuanceRequest::distinct()->orderBy('vehicle','asc')->get(['vehicle','vehicle_id','plate_no']);

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry->whereBetween('created_at',[$_GET['startdate'].' 00:00:00',$_GET['enddate'].' 23:59:59']);
        } else {
            $qry->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()]); 
        }

        $qry->where(function($query) use ($request) {
            if(!is_null($request->department) )
                $query->where('dept', $request->department);
        });

        $qry->where(function($query) use ($request) {
            if(!is_null($request->fuel_type))
                $query->where('fuel_type', $request->fuel_type);
        });

        $qry->where(function($query) use ($request) {
            $query->where('location', $request->location);
        });

        

        // if(request()->has('department') && request()->department != "0") {
        //     $qry->where('dept', request()->department);
        // }

        // if(request()->has('fuel_type') && request()->fuel_type != "0") {
        //     $qry->where('fuel_type', request()->fuel_type);
        // }

        // if(request()->has('location') && request()->location != "0") {
        //     $qry->where('fuel_type', request()->fuel_type);
        // }

        $rs = $qry->orderBy('created_at','desc')->get();


        return view('reports.issued-issuances',compact('rs','depts','fuel_types','vehicles'));
    }

    public function pendingIssuances(Request $request)
    {
        $qry = IssuanceRequest::whereNotNull('id')->where('status', 'PENDING');
        $depts = IssuanceRequest::distinct()->orderBy('dept','asc')->get(['dept']);
        $fuel_types = Fuels::all()->pluck('fuel_type');
        $vehicles = IssuanceRequest::distinct()->orderBy('vehicle','asc')->get(['vehicle','vehicle_id','plate_no']);

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry->whereBetween('created_at',[$_GET['startdate'].' 00:00:00',$_GET['enddate'].' 23:59:59']);
        } else {
            $qry->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()]); 
        }

        $qry->where(function($query) use ($request) {
            if(!is_null($request->department) )
                $query->where('dept', $request->department);
        });

        $qry->where(function($query) use ($request) {
            if(!is_null($request->fuel_type))
                $query->where('fuel_type', $request->fuel_type);
        });

        $qry->where(function($query) use ($request) {
            $query->where('location', $request->location);
        });

        $rs = $qry->orderBy('created_at','desc')->get();


        return view('reports.pending-issuances',compact('rs','depts','fuel_types','vehicles'));
    }


}
