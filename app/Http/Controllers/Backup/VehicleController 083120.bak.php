<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

use App\IssuanceRequest;
use App\Vehicles;

class VehicleController extends Controller
{
    public function index(){

        $vehicles = Vehicles::where('isECS',0)->orderBy('name', 'asc')->paginate(10); 

        return view('vehicle.index',compact('vehicles'));
    }

    public function store(Request $req)
    {
        Vehicles::create([
            'name' => $req->name,
            'plateno' => $req->plateno,
            'vehicle_code' => $req->ccode,
            'type' => $req->type,
            'dept' => $req->dept,
            'isECS' => 0
        ]);

        return redirect(route('vehicle.lists'))->with('successMsg', 'Vehicle has been added.');
    }

    public function vehicleInfo($id){

        $qry = IssuanceRequest::where('vehicle_id',$id);
        $details = Vehicles::find($id);

        $issuances = $qry->where('status','ISSUED')->orderBy('id','desc')->paginate(10);
        $total_actual_qty = $qry->where('status','ISSUED')->sum('actual_qty');
        
        return view('vehicle.summary',compact('details','issuances','total_actual_qty'));
    }

    public function update(Request $req)
    {
        Vehicles::find($req->vid)->update([
            'name' => $req->name,
            'plateno' => $req->plateno,
            'vehicle_code' => $req->ccode,
            'type' => $req->type,
            'dept' => $req->dept
        ]);

        return back()->with('successMsg','Vehicle details has been updated.');
    }

    public function delete(Request $req)
    {
        Vehicles::find($req->vid)->delete();

        return back()->with('successMsg', 'Vehicle has been deleted.');
    }

}
