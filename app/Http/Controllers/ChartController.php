<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class ChartController extends Controller
{
    public function monthlyServedFuel(Request $req){
        if($req->ajax()){
            $data = array();
            $result = DB::table('v_monthly_served_fuel')->select('mo','total_liters')->orderBy('mos','asc')->get();

            foreach($result as $row){
                $data[] = $row;
            }
           return Response($data);
        }
    }

    // public function fuelTypeConsumption(Request $req){
    //     if($req->ajax()){
    //         $data = array();

    //         $result = DB::table('v_fuel_type_monthly_consu')->select('month_id','petron95','octane_ecs','petron91','octane','petron','diesel','phoenix91','phoenix')
    //             ->orderBy('year','month_id','asc')
    //             ->get();
            
    //         foreach($result as $row){
    //             $data[] = $row;
    //         }
    //         return Response($data);
    //     }
    // }

    public function fuelConsu(Request $req){
        if($req->ajax()){

            $result = DB::select("select rq,COALESCE(odo - LAG(odo, 1) OVER (order by id),0) as distance,
            COALESCE(odo - LAG(odo, 1) OVER (order by id),0)/liter as avg_consumption
                FROM fms_fuel_request_tbl where vehicle = '".$req->vid."' and is_issued = 1");

            return response($result);
        }
    }

    public function monthlyFuelConsuPerVehicle(Request $req){
        if($req->ajax()){

            $result = DB::table('v_monthly_fuel_consu_per_vehicle')->select('mos','total_liters','distance')
                ->where('vehicle','=',$req->vid)
                ->orderBy('mo','asc')
                ->get();

            return Response($result);
        }
    }

    public function deptBudgetVsConsu(Request $req){
        if($req->ajax()){
            $dt = date('m');

            if(Auth::user()->is_dept == 1){
                $result = DB::table('v_dept_budget_vs_consumption')->select('dept','perc_budget','consumption')
                    ->where('dept','=',Auth::user()->dept)
                    ->where('mo','=',$dt)
                    ->where('consumption','>',0)
                    ->orderBy('consumption','desc')
                    ->take(10)
                    ->get();
            } else {
                $result = DB::table('v_dept_budget_vs_consumption')->select('dept','perc_budget','consumption')
                    ->where('mo','=',$dt)
                    ->where('consumption','>',0)
                    ->orderBy('consumption','desc')
                    ->take(10)
                    ->get();
            }
        
            return Response($result);
        }
    }

    public function fuelMonthlyConsu(Request $req){
        if($req->ajax()){
            $now = date('Y');

            if(Auth::user()->is_dept == 1){
                $dept = Auth::user()->dept;
                
                $result = DB::select("select department, mName, mOrder, sum(total_liter) as total_liter from v_monthly_fuel_served_per_dept where ryear = ".$now." and department = '".$dept."' group by mOrder, mName, department order by mOrder asc");

            } else {
                $result = DB::select("select mName, mOrder, sum(total_liter) as total_liter from v_monthly_fuel_served_per_dept 
                where ryear = ".$now." group by mOrder, mName, ryear order by mOrder asc");
            }

            return Response($result);
        }
    }

    public function avgFuelConsuDiff(Request $req){
        if($req->ajax()){
            $output = "";

            if(Auth::user()->is_dept == 1){
                $dept = Auth::user()->dept;
                $data = DB::select("select d.*, v.dept from v_vehicle_fuel_consu_diff as d inner join v_vehicle_tbl as v on d.vehicle = v.id where d.fuel_diff < -4 and dept = '".$dept."' order by d.fuel_diff desc");
            } else {
                $data = DB::select("select d.*, v.dept from v_vehicle_fuel_consu_diff as d inner join v_vehicle_tbl as v on d.vehicle = v.id where d.fuel_diff < -4 order by d.fuel_diff desc");
            }
            
            $rows = count($data);
            
            if($rows > 0){
                foreach($data as $key => $d){
                    $output .= '<tr>'.
                                '<td><a style="color:white;" href="/vehicle/details/'.$d->vehicle.'" target="_blank">'.$d->name.'</a></td>'.
                                '<td>'.number_format($d->fuel_diff,0).' <small>KM / L</small></td>'.
                            '</tr>';
                }
                return Response($output);
            }
        }
    }

    public function avgFuelConsuBaseKML(Request $req){
        if($req->ajax()){
            $output = "";

            if(Auth::user()->is_dept == 1){
                $dept = Auth::user()->dept;
                $data = DB::select("select dept, vehicle,name, avg(avg_fuel_consu) as avgKML from v_average_kml where dept = '".$dept."' group by dept, vehicle,name order by avgKML desc");
            } else {
                $data = DB::select("select dept, vehicle,name, avg(avg_fuel_consu) as avgKML from v_average_kml group by dept, vehicle,name order by avgKML desc");
            }
            
            $rows = count($data);
            
            if($rows > 0){
                foreach($data as $key => $d){
                    $output .= '<tr>'.
                                '<td><a style="color:white;" href="/vehicle/details/'.$d->vehicle.'" target="_blank">'.$d->name.'</a></td>'.
                                '<td>'.number_format($d->avgKML,0).' <small>KM / L</small></td>'.
                            '</tr>';
                }
                return Response($output);
            }
        }
    }

    public function fuelMonthlyIssuance(Request $req){
        if($req->ajax()){
            $data =DB::table('v_fuel_monthly_issuance_cost')->select('mos','total_liters','total_cost')->where('vehicle','=',$req->vid)->orderBy('mo','asc')->get();

            return Response($data);
        }
    }

}
