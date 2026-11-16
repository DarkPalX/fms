<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tank;
use App\FuelDelivery;
use App\FuelDeliveryDetail;
use App\DipstickMeasurement;
use App\DipstickMeasurementDetail;
use App\PrePostOperation;
use Illuminate\Database\Eloquent\Builder;

class PrePostOperationController extends Controller
{

    public function index(Request $request) {
        
        $tanks = Tank::where('is_active', 1)->get();

        if($request->has('tank_name') && $request->tank_name != '') {
            $pre_post_operations = PrePostOperation::where('created_at', '>=', \Carbon\Carbon::parse($request->date_from))
                ->where('created_at', '<=', \Carbon\Carbon::parse($request->to))
                ->whereHas('dipstick_measurement', function(Builder $query) use ($request) {
                    $query->where('tank_name', $request->tank_name);
                })
                ->paginate(20);
        } else {
            $pre_post_operations = PrePostOperation::paginate(20);
        }

        return view('fuel.pre-post-operation.index', compact('pre_post_operations', 'tanks'));

    }

    public function create() {

        $dipstick_measurements = DipstickMeasurement::whereDate('date_delivered', \Carbon\Carbon::yesterday())
            ->orWhereDate('date_delivered', \Carbon\Carbon::now())
            ->where('transaction_type', 'wetstock')->get();
        $dipstick_measurements = $dipstick_measurements->groupBy('tank_name');

        $d_ids = DipstickMeasurement::whereDate('date_delivered', \Carbon\Carbon::yesterday())
            ->orWhereDate('date_delivered', \Carbon\Carbon::now())
            ->where('transaction_type', 'wetstock')->pluck('id');

        $data = [];

        foreach( $dipstick_measurements as $key => $measurement ) {
            $data[$key] = [];
            $am = 0;
            $am_l = 0;
            $am_td = 0;
            $am_tm = 0;
            $pm = 0;
            $pm_l = 0;

            foreach($measurement as $msrmnt) {
                if(\Carbon\Carbon::parse($msrmnt->date_delivered)->isToday()) {
                    $pm = $pm + $msrmnt->cm;
                    $pm_l = $pm_l + $msrmnt->to_liter;
                } else {                    
                    $am = $am + $msrmnt->cm;
                    $am_l = $am_l + $msrmnt->to_liter;
                    $am_td = $msrmnt->totalizer_digital;
                    $am_tm = $msrmnt->totalizer_mechanical;
                }
            }

            $data[$key] = [
                'am'    => $am / 3 ,
                'am_l'  => $am_l / 3 ,
                'am_td' => $am_td ,
                'am_tm' => $am_tm ,
                'pm'    => $pm / 3 ,
                'pm_l'  => $pm_l / 3
            ];
        }

        return view('fuel.pre-post-operation.create', compact('data', 'd_ids'));

    }

    public function store(Request $request) {

        $d_ids = json_decode($request->delivery_dipsticks);

        $pre_post_operation = PrePostOperation::create([
            'remarks'           => $request->remarks ,
            'conducted_by'      => $request->conducted_by ,
            'assisted_by'       => $request->assisted_by ,
            'witnessed_by'      => $request->witnessed_by ,
            'received_by'       => $request->received_by
        ]);       

        foreach( $d_ids as $id ) {
            $d_m = DipstickMeasurement::find($id);

            $tank_name = strtolower(str_replace(" ", "_", $d_m->tank_name));
            
            $b_d_totalizer = $tank_name."_totalizer_digital_begin";
            $b_m_totalizer = $tank_name."_totalizer_mechanical_begin";
            $e_d_totalizer = $tank_name."_totalizer_digital_end";
            $e_m_totalizer = $tank_name."_totalizer_mechanical_end";
            $b_l = $tank_name."_to_liter_begin";
            $e_l = $tank_name."_to_liter_end";
			
            if(\Carbon\Carbon::parse($d_m->date_delivered)->isToday()) {
                $d_m->update([
                    'pre_post_operation_id' => $pre_post_operation->id, 
                    'totalizer_digital' => $request->{$e_d_totalizer} , 
                    'totalizer_mechanical' => $request->{$e_m_totalizer}
                ]);
            } else {
                $d_m->update([
                    'pre_post_operation_id' => $pre_post_operation->id, 
                    'totalizer_digital' => $request->{$b_d_totalizer} , 
                    'totalizer_mechanical' => $request->{$b_m_totalizer}
                ]);
            }
        }

        return redirect()->route('pre-post-operations.index')->with('successMsg', 'Pre Post Operation is successfully created.');
        
    }

}