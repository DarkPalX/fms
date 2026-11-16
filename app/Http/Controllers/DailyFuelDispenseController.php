<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tank;
use App\FuelDelivery;
use App\FuelDeliveryDetail;
use App\DipstickMeasurement;
use App\DipstickMeasurementDetail;
use App\IssuanceRequest;
use App\DailyDispenseIssuance;
use App\DailyFuelDispense;

class DailyFuelDispenseController extends Controller
{

    public function index(Request $request) {

        $tanks = Tank::where('is_active', 1)->get();

        if($request->has('tank_name') && $request->tank_name != '') {
            $daily_dispences = DailyFuelDispense::where('tank_id', $request->tank_name)
                ->where('date_created', '>=', \Carbon\Carbon::parse($request->date_from))
                ->where('date_created', '<=', \Carbon\Carbon::parse($request->to))
                ->paginate(20);
        } else {
            $daily_dispences = DailyFuelDispense::latest()->paginate(20);
        }

        return view('fuel.daily-dispenses.index', compact('daily_dispences', 'tanks'));

    }

    public function create() {

        $tanks = Tank::where('is_active', 1)->get();

        return view('fuel.daily-dispenses.create', compact('tanks'));

    }

    public function store(Request $request) {

        $daily_dispence = new DailyFuelDispense;
        $daily_dispence->tank_id = $request->tank_id;
        $daily_dispence->description = $request->description;
        $daily_dispence->beginning_dipstick_measurement = $request->beginning_dipstick_measurement != null ? $request->beginning_dipstick_measurement : 0;
        $daily_dispence->beginning_totalizer_reading = $request->beginning_totalizer_m_reading != null ? $request->beginning_totalizer_m_reading  : $request->beginning_totalizer_d_reading ;
        $daily_dispence->ending_dipstick_measurement = $request->ending_dipstick_measurement;
        $daily_dispence->ending_totalizer_reading = $request->ending_totalizer_m_reading != null ? $request->ending_totalizer_m_reading : $request->ending_totalizer_d_reading;
        $daily_dispence->total_dispensed = $request->total_issuance;
        $daily_dispence->remarks = $request->remarks;
        $daily_dispence->prepared_by = $request->prepared_by;
        $daily_dispence->encoded_by = $request->encoded_by;
        $daily_dispence->received_by = $request->received_by;
        $daily_dispence->save();

        $issuances = explode(",", $request->issuances);

        foreach($issuances as $issuance) {
            DailyDispenseIssuance::create(
                [
                    'issuance_id' => $issuance, 
                    'daily_fuel_dispense_id' => $daily_dispence->id
                ]
            );
        }

        return redirect()->route('daily-fuel-dispenses.index')->with('successMsg', 'Daily Dispense Report has been created.');

    }

    public function getRelatedDetailOfTank(Request $request) {

        if( $request->has('tank') && $request->tank != '') {
            
            $tank = Tank::find($request->tank);
            $tank_d_measurements = DipstickMeasurement::where('tank_name', $tank->name)
                ->whereDate('date_delivered', \Carbon\Carbon::now())
                ->orWhereDate('date_delivered', \Carbon\Carbon::yesterday())
                ->get();

            if( count($tank_d_measurements) < 5 ) {
                return response()->json(['error' => false]);
            }

            $morning = [];
            $afternoon = [];
            $totalizer_r_m_b = 0;
            $totalizer_r_d_b = 0;
            $totalizer_r_m_e = 0;
            $totalizer_r_d_e = 0;

            foreach($tank_d_measurements as $d_measurement) {
                if( \Carbon\Carbon::parse($d_measurement->date_delivered)->isToday() && $d_measurement->transaction_type == 'wetstock' ) {
                    array_push($afternoon, $d_measurement->to_liter);
                    $totalizer_r_d_e = $d_measurement->totalizer_digital;
                    $totalizer_r_m_e = $d_measurement->totalizer_mechanical;
                } else if(\Carbon\Carbon::parse(!$d_measurement->date_delivered)->isToday() && $d_measurement->transaction_type == 'wetstock') {
                    array_push($morning, $d_measurement->to_liter);
                    $totalizer_r_d_b = $d_measurement->totalizer_digital;
                    $totalizer_r_m_b = $d_measurement->totalizer_mechanical;
                }
            }

            $morning_collection = Collect($morning);
            $morning_d_avg = $morning_collection->avg();

            $afternoon_collection = Collect($afternoon);
            $afternoon_d_avg = $afternoon_collection->avg();            

            $issuances = IssuanceRequest::where('tank_id', $tank->id)   
                ->whereDate('processed_at', '=', \Carbon\Carbon::now()->format('Y-m-d'))
                ->whereStatus('ISSUED')
                ->get();

            $delivery = FuelDeliveryDetail::where('tank_name', $tank->name)
                ->whereDate('created_at', '=', \Carbon\Carbon::now()->format('Y-m-d'))
                ->first();

            $data = [
                'tank'  => $tank ,
                'morning_d_avg' => $morning_d_avg ,
                'afternoon_d_avg'   => $afternoon_d_avg ,
                'issuances' => $issuances ,
                'fuel_delivered'    => $delivery->actual_delivered_in_liters ,
                'totalizer_m_b'   => $totalizer_r_m_b ,
                'totalizer_m_e'   => $totalizer_r_m_e ,
                'totalizer_d_b'   => $totalizer_r_d_b ,
                'totalizer_d_e'   => $totalizer_r_d_e 
            ];

            return response()->json($data);

        }

    }

}