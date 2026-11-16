<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\FuelSetting;
use App\Logs;

class FuelSettingsController extends Controller
{

    public function index(){

    	$setting = FuelSetting::find(1);

    	return view('fuel.setting', compact('setting'));	
    }

    public function store(Request $request) {

    	$this->validate($request, [
    		'odo'		=> 'required|numeric' ,
    		'fuel'		=> 'required|numeric'
    	]);

    	FuelSetting::create([
    		'odo_max' 		=> $request->odo ,
    		'fuel_max'		=> $request->fuel
    	]);

    }

    public function update(Request $request, $id) {

    	$this->validate($request, [
    		'odo'		=> 'required|numeric' ,
    		'fuel'		=> 'required|numeric'
    	]);
    		
    	$setting = FuelSetting::find($id);

    	$setting->update([
    		'odo_max' 		=> $request->odo ,
    		'fuel_max'		=> $request->fuel
    	]);

    	return back();

    }

}
