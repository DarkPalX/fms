<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tank;
use App\FuelDelivery;
use App\FuelDeliveryDetail;
use App\DipstickMeasurement;
use App\DipstickMeasurementDetail;

class FuelWetstockController extends Controller
{

    public function index() {

        return view('fuel.wetstock.index');

    }

    public function create(Request $request) {

        $tanks = Tank::where('is_active', 1)->get();

        return view('fuel.wetstock.create', compact('tanks'));

    }


}


