<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

// use App\IssuanceRequest;
// use App\Vehicles;

class DashboardController extends Controller
{
    public function dashboard(){

        //$vehicles = Vehicles::where('isECS',0)->orderBy('name', 'asc')->paginate(10); 

        return view('dashboard');
    }

}
