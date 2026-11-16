<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

use App\FuelSetting;
use App\IssuanceRequest;
use App\ApprovalRequest;
use App\ProjectCode;
use App\PhaseCode;
use App\Vehicles;
use App\Drivers;
use App\FuelHistory;
use App\Fuels;
use App\Users;
use App\RQ;
use App\FuelApprovalRequestHistory;
use App\Tank;

class FuelController extends Controller
{

    public function __construct()
    {   
        // $this->conn_dms = DB::connection('sqlsrv_dms'); // LICENSE/DRIVER MONITORING SYSTEM DB CONNECTION

        // $this->drivers  = $this->conn_dms->table('tbl_driver_details')->join('tbl_driver_license', 'tbl_driver_license.license_id','=','tbl_driver_details.id')->select('id','fName','mName','lName','expiry_dt')->get();
    }

// Main Controller
    public function home(){
        $data = ViewVehicles::get();

        return view('vehicle.home',compact('data'));
    }
    
    public function index(){

        $issuances  = IssuanceRequest::where('status','ISSUED');

        if(auth()->user()->role == 'gas man' || auth()->user()->role == 'clerk'){

            $issuances->where('processed_by',auth()->user()->domainAccount);
        }

        $issuances = $issuances->orderBy('created_at','desc')->paginate(10);

        return view('fuel.index',compact('issuances'));
    }

    public function pending(){
        
        $issuances   = IssuanceRequest::where('status','PENDING')->orderBy('created_at','desc')->paginate(10);
        
        return view('fuel.pending',compact('issuances'));
    }

    public function process($id){

        $data = IssuanceRequest::find($id);

        $previous = IssuanceRequest::where('vehicle_id',$data->vehicle_id)->where('id','<>',$id)->where('status','ISSUED')->orderBy('id','desc')->first();        

        $data->update(['isLock' => 1]);

        $setting =  FuelSetting::find(1);
        $tanks = Tank::where('is_active', 1)->get();

        return view('fuel.process',compact('data','previous','setting', 'tanks'));
    }

    public function complete(Request $req)
    {                   
        $a = IssuanceRequest::find($req->request_id);

        $vehicle_pending_issuances = IssuanceRequest::where('vehicle_id',$a->vehicle_id)
            ->where('id','<', $a->id)
            ->where('status','PENDING')
            ->get();

        if(count($vehicle_pending_issuances) > 0) {
            //return redirect()->back()->with('errorMsg','There are pending issuance/s issued prior this issuance please process it first..');
        }

        $fuel_cost = 0;
        if($a) {
            $b = Fuels::where('fuel_type', $a->fuel_type)->first();

            if($b) $fuel_cost = $b->cost;
        }

        $a->update([
            'actual_qty' => $req->actual_qty,
            'odo' => $req->odo,
            'processed_by' => Auth::user()->domainAccount,
            'processed_at' => Carbon::now(),            
            'type' => $req->type,
            'status'    => 'ISSUED',
            'location' => $req->location,
            'fuel_cost' => $fuel_cost,
            'is_defective_odo' => $req->is_defective_odo ?? 0,
            'type' => $req->type,
            'tank_id'   => $req->tank_id ?? null,
            'issued_date'   => \Carbon\Carbon::now()
        ]);

        FuelApprovalRequestHistory::create([
            'issuance_id'   => $a->id ,
            'approved_by'   => $a->processed_by , 
            'approved_date' => $a->processed_at ,
            'action'        => 'create'
        ]); 

        // reduce tank fuel per issuance

        return redirect(route('view.issuance',$req->request_id))->with('successMsg','Fuel Request has been processed.');
    }

    public function print($id){
        $data = IssuanceRequest::where('id',$id)->first();

        return view('fuel.print-issuance',compact('data'));
    }

    public function view($id){

        $data  = IssuanceRequest::where('id',$id)->first();

        return view('fuel.view',compact('data'));
    }

    public function approval_index(){
        $requests = ApprovalRequest::where('status','<>','PENDING')->orderBy('id','desc')->paginate(10);

        return view('fuel.approval',compact('requests'));
    }

    public function pending_approval(){
        $requests = ApprovalRequest::where('status','PENDING')->orderBy('id','desc')->paginate(10);

        return view('fuel.approval-pending',compact('requests'));
    }
    

    public function view_request($id)
    {   
        $rs = ApprovalRequest::find($id);

        if($rs->request_type == 'fuel'){
            $data = IssuanceRequest::find($rs->transaction_id);
        } else {
            $data = RQ::find($rs->transaction_id);
        }
        

        return view('fuel.request-details',compact('data','rs'));
    }

    public function approve(Request $req)
    {
        $qry = ApprovalRequest::find($req->rqid);

        $qry->update([
            'status' => $req->status,
            'approver' => auth()->user()->name,
            'approved_at' => Carbon::now(),
        ]);

        if($qry->request_type == 'fuel'){
            IssuanceRequest::find($qry->transaction_id)->update([
                'isEditable' => 1,
                'approval_req_id' => $qry->id
            ]);
        } else {
            RQ::find($qry->transaction_id)->update([
                'isEditable' => 1
            ]);
        }

        return redirect(route('approval-pending.list'))->with('successMsg', 'Request has been approved.');
    }

    public function disapprove(Request $req)
    {
        $qry = ApprovalRequest::find($req->rqid);

        $qry->update([
            'status' => $req->status,
            'remarks' => $req->remarks,
            'approver' => auth()->user()->name,
            'approved_at' => Carbon::now(),
        ]);

        return redirect(route('approval-pending.list'))->with('successMsg', 'Request has been disapproved.');
    }

    public function manual()
    {
        $vehicles   = Vehicles::orderBy('name','asc')->get();
        $rqs        = RQ::get(); // list og 20 naay search box fetch data on keyup
        $phasecodes = PhaseCode::get();
        $drivers    = DB::connection('sqlsrv_dms')->table('driver_details')->join('driver_licenses', 'driver_licenses.driver_id','=','driver_details.id')->select('driver_id','first_name','middle_name','last_name','expiry_at')->get(); 
        $fuels      = Fuels::get();
        $gasman     = Users::where('role','gas man')->get();
        $requestor  = DB::connection('sqlsrv_dportal')->table('users')->select('fullname','dept_name')->where('role','<>','admin')->get();

        return view('fuel.manual',compact('vehicles','rqs','fuels','drivers','phasecodes','gasman','requestor'));
    }

    public function unpost(Request $req)
    {
        ApprovalRequest::create([
            'transaction_id' => $req->rqid,
            'status' => 'PENDING',
            'initiator' => auth()->user()->name,
            'initiated_at' => now(),
            'reason' => $req->remarks,
            'request_type' => $req->type
        ]); 
        
        return back()->with('successMsg',' Fuel issuance unpost request has been submitted.');
    }

    public function edit_issued($id){

        $data = IssuanceRequest::find($id);
        $data->update(['isLock' => 1]);
        $previous = IssuanceRequest::where('vehicle_id',$data->vehicle_id)->where('id','<>',$id)->where('status','<>','CANCELLED')->orderBy('id','desc')->first(); 
        $setting = FuelSetting::find(1);
        return view('fuel.edit-issued-request',compact('data','setting','previous'));
    }

    public function update_issued(Request $req)
    {

        $a = IssuanceRequest::find($req->request_id);

        $a->update([
            'actual_qty' => $req->actual_qty,
            'odo' => $req->odo,
            'type' => $req->type,
            'location' => $req->location,
            'isEditable' => 0
        ]);

        FuelApprovalRequestHistory::create([
            'issuance_id'   => $a->id ,
            'approved_by'   => $a->processed_by , 
            'approved_date' => $a->processed_at ,
            'action'        => 'update'
        ]); 

        return back()->with('successMsg','Fuel issuance details has been updated.');
    }

    public function unlock_request(Request $req)
    {
        $qry = IssuanceRequest::find($req->rqid);
        $qry->update(['isLock' => 0]);

        return redirect(route('fuel.pending-request'))->with('successMsg','Fuel request #'.$qry->uniq_code.' has been unlocked.');
    }

    public function fuelCostAlignment() {

        $issuances = IssuanceRequest::where('status', 'ISSUED')
            ->whereNull('fuel_cost')
            ->whereNotNull('fuel_type')
            ->orderBy('created_at', 'ASC')
            // ->take(2)
            ->get();

        foreach( $issuances as $issuance ) {

            if(is_null($issuance->fuel_type)) continue;

            $fuel_type = Fuels::where('fuel_type', $issuance->fuel_type)->first();

            if($fuel_type) {
                $fuel_type_history = FuelHistory::where('fuel_id', $fuel_type->id)
                    ->whereDate('updated_at', '>', $issuance->created_at)
                    ->first();

                if($fuel_type_history) {
                    $issuance->fuel_cost = $fuel_type_history->cost;
                    $issuance->save();
                } else {
                    $issuance->fuel_cost = $fuel_type->cost;
                    $issuance->save();
                }

            }

        }

        dd($issuances);

    }
    // 
    public function fixOdoIssue($vehicle_id, $plate_no, $status, $from, $odo) {

        $issuances = IssuanceRequest::where('id', '>', $from)
            ->where('vehicle_id', $vehicle_id)
            ->where('plate_no', $plate_no)
            ->where('status', $status)
            ->get();

        foreach( $issuances as $issuance ) {
            $issuance->odo = $issuance->odo + $odo;
            $issuance->save();
        } 

    }

}
