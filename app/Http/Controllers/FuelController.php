<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

use App\FuelSetting;
use App\IssuanceRequest;
use App\IssuanceUpdateLog;
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
use App\AllowEditLogs;
use App\Services\WfsService;

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

    public function change_driver(Request $request){

        //$data = IssuanceRequest::where('driver',$request->new_driver)->first();
        $x = explode("|", $request->new_driver);
        $update  = IssuanceRequest::where('id',$request->issuance_id)->update([
            'driver' => $x[1],
            'driver_id' => $x[0],
            'driver_change_reason' => $request->driver_change_reason
        ]);
        return back()->with('successMsg', 'Driver successfully changed.');
        //$data = ViewVehicles::get();

        //return view('vehicle.home',compact('data'));
    }
    
    public function index(){

        // $cost_codes = IssuanceRequest::select('cost_code')->distinct()->orderBy('cost_code')->pluck('cost_code');

        $phase_codes = IssuanceRequest::select('cost_code')
            ->whereNotNull('cost_code')
            ->get()
            ->map(function ($item) {
                $parts = explode('.', $item->cost_code);
                return $parts[1] ?? null; 
            })
            ->filter()   
            ->unique()   
            ->sort()
            ->values(); 

        $users = Users::all();

        
        if(isset($_GET['search'])){
            $issuances   = IssuanceRequest::where('id','<>','-1');
            
            if(isset($_GET['vehicle']) && strlen($_GET['vehicle']) >= 2){
                $issuances = $issuances->where('plate_no','like', '%'.$_GET['vehicle'].'%');
            }

            if(isset($_GET['driver']) && strlen($_GET['driver']) >= 2){
                $issuances = $issuances->where('driver','like', '%'.$_GET['driver'].'%');
            }

            if(isset($_GET['trans']) && strlen($_GET['trans']) >= 2){
                $issuances = $issuances->where('uniq_code','like', '%'.$_GET['trans'].'%');
            }

            if(isset($_GET['dept']) && strlen($_GET['dept']) >= 2){
                $issuances = $issuances->where('dept','like', '%'.$_GET['dept'].'%');
            }

            if(isset($_GET['rq']) && strlen($_GET['rq']) >= 2){
                $issuances = $issuances->where('rq','like', '%'.$_GET['rq'].'%');
            }

            if(isset($_GET['expense']) && strlen($_GET['expense']) >= 2){
                $issuances = $issuances->where('cost_code','like', '%'.$_GET['expense'].'%');
            }

            // if(auth()->user()->role == 'gas man' || auth()->user()->role == 'clerk'){

            //     $issuances->where('processed_by',auth()->user()->domainAccount);
            // }

            
            $issuances = $issuances->orderBy('created_at','desc')->paginate(10);
        }
        else{

            $issuances  = IssuanceRequest::where('status','ISSUED');

            if(auth()->user()->role == 'gas man' || auth()->user()->role == 'clerk'){

                $issuances->where('processed_by',auth()->user()->domainAccount);
            }

            $issuances = $issuances->orderBy('created_at','desc')->paginate(10);
        }

        return view('fuel.index',compact('issuances', 'phase_codes', 'users'));
    }

    public function pending(){
        
        if(isset($_GET['search'])){
            $issuances   = IssuanceRequest::where('status','PENDING')->whereRaw("(wfs_status IS NULL OR wfs_status != 'PENDING')");
            // $issuances   = IssuanceRequest::where('status','PENDING');
            
            if(isset($_GET['vehicle']) && strlen($_GET['vehicle']) >= 2){
                $issuances = $issuances->where('plate_no','like', '%'.$_GET['vehicle'].'%');
            }

            if(isset($_GET['driver']) && strlen($_GET['driver']) >= 2){
                $issuances = $issuances->where('driver','like', '%'.$_GET['driver'].'%');
            }

            if(isset($_GET['trans']) && strlen($_GET['trans']) >= 2){
                $issuances = $issuances->where('uniq_code','like', '%'.$_GET['trans'].'%');
            }

            if(isset($_GET['dept']) && strlen($_GET['dept']) >= 2){
                $issuances = $issuances->where('dept','like', '%'.$_GET['dept'].'%');
            }
            
            $issuances = $issuances->orderBy('created_at','desc')->paginate(10);
        }
        else{
            $issuances   = IssuanceRequest::where('status','PENDING')->whereRaw("(wfs_status IS NULL OR wfs_status != 'PENDING')")->orderBy('created_at','desc')->paginate(10);
            // $issuances   = IssuanceRequest::where('status','PENDING')->orderBy('created_at','desc')->paginate(10);
        }

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
        $oldValue = clone $a;
        $updates = [];
        $logEntries = [];
        $publicPath = null;

        if ($req->hasFile('attachment')) {
            $file = $req->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('fuel_requests/attachments'), $filename);
            $publicPath = 'fuel_requests/attachments/' . $filename;
            $updates['attachment'] = $publicPath;
        }

        // Fields to update if present
        $fields = ['actual_qty', 'odo', 'type', 'location', 'status'];

        foreach ($fields as $field) {
            if ($req->filled($field)) {
                $updates[$field] = $req->{$field};
            }
        }

        // Always set isEditable to 0
        $updates['isEditable'] = 0;

        // If there are updates, perform them and log changes
        if (!empty($updates)) {
            $a->update($updates);

            // Approval history (only when non-attachment fields exist)
            if (count($updates) > 1 || !isset($updates['attachment'])) {
                FuelApprovalRequestHistory::create([
                    'issuance_id'   => $a->id,
                    'approved_by'   => $a->processed_by,
                    'approved_date' => $a->processed_at,
                    'action'        => 'update',
                ]);
            }

            // Compare and prepare log entries
            foreach ($updates as $field => $newValue) {
                $old = $oldValue->{$field};

                // Compare as strings to handle type differences
                if ((string) $old !== (string) $newValue) {
                    $logEntries[] = [
                        'field'     => $field,
                        'old_value' => $old,
                        'new_value' => $newValue,
                    ];
                }
            }

            // Save log if any field actually changed
            if (!empty($logEntries)) {
                IssuanceUpdateLog::create([
                    'request_id'  => $a->id,
                    'attachment'  => $publicPath,
                    'user_id'     => auth()->id(),
                    'update_logs' => json_encode($logEntries),
                ]);
            }
        }

        return back()->with('successMsg', 'Fuel issuance details have been updated.');
    }

    public function update_issuance_cost_code(Request $request)
    {
        $issuance = IssuanceRequest::find($request->issuance_id);

        $issuance->update([
            'cost_code' => $request->vehicle_code . '.' . $request->phase_code . '.' . $request->fuel_code
        ]);

        return redirect()->back()->with('successMsg', 'Fuel issuance expense cost code have been updated.');
    }

    public function update_issuance_processed_by(Request $request)
    {
        $issuance = IssuanceRequest::find($request->issuance_id);

        $formattedDate = Carbon::parse($request->processed_at)->toDateTimeString();

        // dd($formattedDate);

        $issuance->update([
            'old_processed_by' => $issuance->processed_by,
            'old_processed_at' => $issuance->processed_at,
            'processed_by' => $request->processed_by,
            'processed_at' => $formattedDate
        ]);

        FuelApprovalRequestHistory::create([
            'issuance_id'   => $issuance->id ,
            'approved_by'   => $issuance->processed_by , 
            'approved_date' => $issuance->processed_at ,
            'action'        => 'update'
        ]); 

        return redirect()->back()->with('successMsg', 'Fuel issuance details have been updated.');
    }

    // public function update_issued(Request $req)
    // {

    //     $a = IssuanceRequest::find($req->request_id);

    //     $a->update([
    //         'actual_qty' => $req->actual_qty,
    //         'odo' => $req->odo,
    //         'type' => $req->type,
    //         'location' => $req->location,
    //         'isEditable' => 0
    //     ]);

    //     FuelApprovalRequestHistory::create([
    //         'issuance_id'   => $a->id ,
    //         'approved_by'   => $a->processed_by , 
    //         'approved_date' => $a->processed_at ,
    //         'action'        => 'update'
    //     ]); 

    //     return back()->with('successMsg','Fuel issuance details has been updated.');
    // }

    public function unlock_request(Request $req)
    {
        $qry = IssuanceRequest::find($req->rqid);
        $qry->update(['isLock' => 0]);

        return redirect(route('fuel.pending-request'))->with('successMsg','Fuel request #'.$qry->uniq_code.' has been unlocked.');
    }

    public function allow_edit_request(Request $req)
    {
        $qry = IssuanceRequest::find($req->rqid);

        $qry->update(['isEditable' => 1]);

        $allow_edit_logs = AllowEditLogs::create([
            'request_id' => $req->rqid,
            'allowed_by' => auth()->user()->id,
            'user_name' => auth()->user()->name
        ]);

        return redirect(route('fuel.pending-request'))->with('successMsg','Fuel request #'.$qry->uniq_code.' is now editable on dportal.');
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

    public function sync_with_wfs()
    {
        try {
            $data = collect(WfsService::sync())->keyBy('id');

            $pendingFuels = IssuanceRequest::where('wfs_status', 'PENDING')->get();

            foreach ($pendingFuels as $fuel) {

                $wfs = $data->get($fuel->wfs_transaction_id);

                if ($wfs && $wfs['status'] === 'FULLY APPROVED') {
                    $fuel->update([
                        'wfs_status' => 'FULLY APPROVED'
                    ]);
                }
            }

            return back()->with('success', 'WFS statuses synced');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // public function sync_with_wfs()
    // {
    //     try {
    //         $data = WfsService::sync([
    //             'date' => now()->toDateString()
    //         ]);

    //         foreach ($data as $item) {
    //             Fuel::updateOrCreate(
    //                 ['external_id' => $item['id']],
    //                 [
    //                     'name' => $item['name'],
    //                     'qty'  => $item['qty'],
    //                 ]
    //             );
    //         }

    //         return back()->with('success', 'Synced successfully');

    //     } catch (\Exception $e) {
    //         return back()->with('error', $e->getMessage());
    //     }
    // }

    // public function sync_with_wfs()
    // {
    //     dd('asd');
    //     $pending_requests = IssuanceRequest::where('status', 'PENDING')
    //         ->whereRaw("(wfs_status IS NULL OR wfs_status != 'PENDING')")
    //         ->get();

    //     foreach($pending_requests as $request) {
    //         $request->wfs_status = 'PENDING';
    //         $request->save();
    //     }

    //     return redirect()->back()->with('successMsg', 'Pending fuel requests have been synced with WFS.');
    // }

}
