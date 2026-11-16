<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tank;
use App\FuelDelivery;
use App\FuelDeliveryDetail;
use App\DipstickMeasurement;
use App\DipstickMeasurementDetail;
use App\Fuels;

class FuelDeliveriesController extends Controller
{

    function __construct() {}

    public function index() {
        
        $deliveries = FuelDelivery::orderBy('id', 'DESC')->paginate(10);

        return view('fuel.deliveries.index', compact('deliveries'));

    }

    public function create() {

        $tanks = Tank::where('is_active', 1)->get();
        $dp_measurement = DipstickMeasurement::where('transaction_type', 'wetstock')
            ->whereDate('date_delivered', \Carbon\Carbon::yesterday())
            ->first();   
        $fuel_types = Fuels::where('isActive', 1)->get();

        return view('fuel.deliveries.create', compact('tanks', 'dp_measurement', 'fuel_types'));

    }

    public function deliveryReport(Request $request) {

        $delivery_dipsticks = json_decode($request->delivery_dipsticks, true);
        $delivery_details = json_decode($request->delivery_details, true);
        $isEmpty = true;

        $req = $this->validate($request, [
            'date_created'  =>'required' ,
            'supplier'      =>'required' ,
            'plate_no'      =>'required' ,
            'fuel_type'     =>'required' ,
            'invoice_no'    =>'required' ,
            'vehicle'       => 'required' ,
            'receipt_no'    =>'required' ,
            'remarks'       => 'required' ,
            'delivered_by'  => 'required' ,
            'witnessed_by'  => 'required' ,
            'attested_by'   => 'required' ,
            'noted_by'      => 'required' ,
            'sg_assigned'   => 'required' ,
        ]);


        foreach( $delivery_details as $key => $detail ) {
            
            $tank_name = str_replace('_', ' ', $key);

            if( !count($detail) ) { 
                unset($delivery_details[$key]);
            }

        }

        $t = null;
        $types = ['phoenix', 'petron', 'gasoline'];
        $fuel_conversion = json_decode(file_get_contents(public_path('data/fuel_conversion2.json')), true);

        if(count($delivery_details)) {

            $delivery = FuelDelivery::create($req);

            foreach( $delivery_details as $key => $detail ) {
                
                $tank_name = str_replace('_', ' ', $key);
                $total_qty_delivered = 0;

                foreach( $types as $type) {
                    if(str_contains($tank_name, $type)) {
                        $t = $type;
                        break;
                    }
                }
                $fuel_type = Fuels::where('fuel_type', 'like', "%{$t}%")->first();  

                foreach($detail as $d) {

                    FuelDeliveryDetail::create([
                        'delivery_id'   => $delivery->id ,
                        'compartment'    => $d['compartment'] ,
                        'capacity'      => $d['capacity'] ,
                        'tank_name'     => $d['name'] ,
                        'delivered_in_liters' => $d['liter'] ,
                        'before_time'   => $d['before_time'] ,
                        'before_cm'     => $d['before_cm'] , 
                        'before_liter'  => $d['before_l'] , 
                        'after_time'    => $d['after_time']."" ,
                        'after_cm'     => $d['after_cm'] , 
                        'after_liter'  => $d['after_l'] , 
                        'actual_delivered_in_liters' => $d['actual_delivered'] ,
                        'dome_cover'    => $d['dome_cover'] ,
                        'discharge_valve'   => $d['discharge_valve']
                    ]);
                    
                    $total_qty_delivered = $total_qty_delivered + (int)$d['actual_delivered'];

                }

                foreach( $delivery_dipsticks[$key] as  $key1 => $dipstick ) {
                    
                    $cm_b = number_format($dipstick['before_cm'] , 2);
                    $cm_a = number_format($dipstick['after_cm'] , 2);

                    $exces_b_val = array_reverse(explode('.', $cm_b)); 
                    $exces_a_val = array_reverse(explode('.', $cm_a)); 

                    if($exces_b_val[0] > 0) {

                        $exces_b = $fuel_conversion[strtolower($tank_name)][$fuel_type->stock_code][$exces_b_val[1]]['excess'];
                        $liter_b = $fuel_conversion[strtolower($tank_name)][$fuel_type->stock_code][$exces_b_val[1]-1]['liters'];
                        $exces_to_liter_b = $exces_b * ($exces_b_val[0] / 100);
            
                    } else {
            
                        $liter_b = $fuel_conversion[strtolower($tank_name)][$fuel_type->stock_code][$exces_b_val[1]-1]['liters'];
                        $exces_to_liter_b = 0;
            
                    }

                    if($exces_a_val[0] > 0) {

                        $exces_a = $fuel_conversion[strtolower($tank_name)][$fuel_type->stock_code][$exces_a_val[1]]['excess'];
                        $liter_a = $fuel_conversion[strtolower($tank_name)][$fuel_type->stock_code][$exces_a_val[1]-1]['liters'];
                        $exces_to_liter_a = $exces_a * ($exces_a_val[0] / 100);
            
                    } else {
            
                        $liter_a = $fuel_conversion[strtolower($tank_name)][$fuel_type->stock_code][$exces_a_val[1]-1]['liters'];
                        $exces_to_liter_a = 0;
            
                    }

                    $dipstick_m = DipstickMeasurement::create([
                        'transaction_from'  => 'delivery' ,
                        'transaction_ref'   => $delivery->id ,
                        'transaction_type'  => 'delivery' ,
                        'fuel_type'         => $fuel_type->fuel_type , 
                        'qty_delivered'     => $total_qty_delivered ,
                        'date_delivered'    => $request->date_created ,
                        'supplier'          => $request->supplier ,  
                        'created_by'        => auth()->user()->domainAccount,
                        'tank_name'         => $tank_name,
                        'dip_no'            => $key1 + 1 ,          
                        'before_midday'     => 1 ,
                        'cm'                => $dipstick['before_cm'] ,
                        'to_liter'          => $liter_b + $exces_to_liter_b ,                 
                    ]);

                    $dipstick_m = DipstickMeasurement::create([
                        'transaction_from'  => 'delivery' ,
                        'transaction_ref'   => $delivery->id ,
                        'transaction_type'  => 'delivery' ,
                        'fuel_type'         => $fuel_type->fuel_type , 
                        'qty_delivered'     => $total_qty_delivered ,
                        'date_delivered'    => $request->date_created ,
                        'supplier'          => $request->supplier ,  
                        'created_by'        => auth()->user()->domainAccount,
                        'tank_name'         => $tank_name,
                        'dip_no'            => $key1 + 1 ,          
                        'before_midday'     => 0 ,
                        'cm'                => $dipstick['after_cm'] ,
                        'to_liter'          => $liter_a + $exces_to_liter_a
                    ]);

                }

            }

            return redirect()->route('fuel-deliveries.index')->with('successMsg', 'Delivery details is successfully saved.');

        }
            
        return back()->with('errorMsg', 'Delivery details is required')->withInput($request->input());

    }

}
