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
use App\Wetstock;

class WetstockController extends Controller
{

    public function index(Request $request) {

        $tanks = Tank::where('is_active', 1)->get();

        if($request->has('tank_name') && $request->tank_name != '') {
            $wetstocks = Wetstock::where('tank_id', $request->tank_name)
                ->whereDate('day', '>=', \Carbon\Carbon::parse($request->date_from))
                ->whereDate('day', '<=', \Carbon\Carbon::parse($request->date_to))
                ->paginate(20);
        } else {
            $wetstocks = Wetstock::latest()->paginate(count($tanks));
        }

        return view('fuel.wetstock.index', compact('wetstocks', 'tanks'));

    }

    public function create(Request $request) {

        $tanks = Tank::where('is_active', 1)->get();

        return view('fuel.wetstock.create', compact('tanks'));

    }

    public function store(Request $request) {

        foreach($request->_data as $data) {

            Wetstock::create([
                'tank_id'   => $data['tank_id'],
                'day'       => \Carbon\Carbon::now()->format('Y-m-d') ,
                'opening_stock' => str_replace(",","",$data['opening_dp_ave']) ,
                'actual_delivered'  => str_replace(",","",$data['actual_delivered']) ,
                'issuance'  => str_replace(",","",$data['issuance']) ,
                'remaining_stock'   => str_replace(",","",$data['remaining_stock']) ,
                'ending_stock'      => str_replace(",","",$data['ending_dp_ave']) ,
                'daily_variance'    => str_replace(",","",$data['daily_variance']) ,
                'cumulative_variance'   => str_replace(",","",$data['cumulative_variance']) ,
                'cumulative_issuance'   => str_replace(",","",$data['cumulative_issuance']) ,
                'cumulative_issuance_percentage'    => str_replace(",","",$data['cumulative_variance_percentage']) ,
                'created_by'    => auth()->user()->domainAccount
            ]);

        }

        return response()->json(['success' => 'true', 'message' => 'Daily Wetstock Report has been created..']);

    }

}