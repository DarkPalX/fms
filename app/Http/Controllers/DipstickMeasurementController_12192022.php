<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tank;
use App\FuelDelivery;
use App\FuelDeliveryDetail;
use App\DipstickMeasurement;
use App\DipstickMeasurementDetail;
use App\Fuels;

class DipstickMeasurementController extends Controller
{

    public function index(Request $request) {

        $tanks = Tank::where('is_active', 1)->get();

        if($request->has('tank_name') && $request->tank_name != '') {
            $dipsticks = DipstickMeasurement::where('tank_name', $request->tank_name)
                ->whereDate('date_delivered', '>=', \Carbon\Carbon::parse($request->date_from))
                ->whereDate('date_delivered', '<=', \Carbon\Carbon::parse($request->date_to))
                ->where('transaction_type', 'wetstock')
                ->get()
                ->groupBy(['tank_name', 'date_delivered']);
        } else {

            $dipsticks = DipstickMeasurement::latest()->where('transaction_type', 'wetstock')
                ->take(20)
                ->get()
                ->groupBy(['tank_name', 'date_delivered']);

        }

        $fuel_types = Fuels::where('isActive', 1)->get();

        return view('fuel.dipstick-measurement.index', compact('tanks', 'dipsticks', 'fuel_types'));

    }

    public function create(Request $request) {

        $tanks = Tank::where('is_active', 1)->get();
        $fuel_types = Fuels::where('isActive', 1)->get();

        return view('fuel.dipstick-measurement.create', compact('tanks', 'fuel_types'));

    }

    public function store(Request $request) {

        $dipstik_measurements = DipstickMeasurement::whereDate('date_delivered', $request->date_created)
            ->where('transaction_type', 'wetstock')
            ->where('tank_name', $request->tank_name)
            ->get();

        $is_midday = \Carbon\Carbon::parse($request->date_created)->format('H');

        $exists = false;

        if(count($dipstik_measurements)) {
            $exists = true;
            // foreach( $dipstik_measurements as $m ) {

            //     $is_midday_m = \Carbon\Carbon::parse($m->date_delivered)->format('H');

            //     if( $is_midday < 12 && $is_midday_m < 12 ) { $exists = true; continue; }
            //     if( $is_midday > 12 && $is_midday_m > 12 ) { $exists = true; continue; }

            // }

            if($exists) 
                return redirect()->route('dipstick-measurements.index')
                    ->with('errorMsg', 'Dipstick Measurement dated '. \Carbon\Carbon::parse($request->date_created)
                    ->format('Y-m-d') .' is done for '. $request->tank_name . '.'); 

        }

        $fuel_conversion = json_decode(file_get_contents(public_path('data/fuel_conversion2.json')), true);
        $fuel_type = Fuels::where('fuel_type', $request->fuel_type)->first();

        for($x = 1; $x <= 3; $x++) {
            $cc = 'cm'.$x;

            $cm = number_format($request[$cc], 2);
            $exces_val = array_reverse(explode('.', $cm));
            
            if($exces_val[0] > 0) {

                $exces = $fuel_conversion[strtolower($request->tank_name)][$fuel_type->stock_code][$exces_val[1]]['excess'];
                $liter = $fuel_conversion[strtolower($request->tank_name)][$fuel_type->stock_code][$exces_val[1]-1]['liters'];
                $exces_to_liter = $exces * ($exces_val[0] / 100);
    
            } else {
                
                $liter = $fuel_conversion[strtolower($request->tank_name)][$fuel_type->stock_code][$exces_val[1]-1]['liters'];
                $exces_to_liter = 0;
                
            }

            $dp_measurement = DipstickMeasurement::create([
                'fuel_type' => $request->fuel_type ,
                'supplier'  => 'none' ,
                'qty_delivered' => 0 ,
                'date_delivered'    => $request->date_created , 
                'transaction_type'  => 'wetstock' ,
                'transaction_from'  => 'wetstock' ,
                'transaction_ref'   => '0' ,
                'created_by'        => auth()->user()->domainAccount ,
                'tank_name' => $request->tank_name ,
                'dip_no'    => $x ,
                'cm'  => $request[$cc],
                'before_midday' => $is_midday > 12 ? 0 : 1 ,
                'to_liter'  => $liter + $exces_to_liter
            ]);

        }

        return redirect()->route('dipstick-measurements.index')->with('successMsg', 'Dipstick Measurement for '. $request->tank_name .' is successfully created.');

    }

    public function getConversion($tank_name, $fuel_type, $cm) {

        $t = null;

        $types = ['phoenix', 'petron', 'gasoline'];

        foreach( $types as $type) {
            if(str_contains(strtolower($tank_name), $type)) {
                $t = $type;
                break;
            }
        }

        $cm = number_format($cm, 2);

        $exces_val = array_reverse(explode('.', $cm));

        $fuel_conversion = json_decode(file_get_contents(public_path('data/fuel_conversion2.json')), true);
        $fuel_type = Fuels::where('fuel_type', 'like', "%{$t}%")->first();        

        if($exces_val[0] > 0) {

            $exces = $fuel_conversion[strtolower($tank_name)][$fuel_type->stock_code][$exces_val[1]]['excess'];
            $liter = $fuel_conversion[strtolower($tank_name)][$fuel_type->stock_code][$exces_val[1]-1]['liters'];
            $exces_to_liter = $exces * ($exces_val[0] / 100);

        } else {

            $liter = $fuel_conversion[strtolower($tank_name)][$fuel_type->stock_code][$exces_val[1]-1]['liters'];
            $exces_to_liter = 0;

        }

        return $liter + $exces_to_liter; 
        //return strtolower($tank_name) . ' - ' . $fuel_type->stock_code . ' - ' . ($cm-1) . ' - ' .'liter';
        // return $fuel_conversion[strtolower($tank_name)][$fuel_type->stock_code][$exces_val[1]-1]['liter'];

    }

}


