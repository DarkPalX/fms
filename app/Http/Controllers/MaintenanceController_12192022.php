<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

// Models
use App\Fuels;
use App\Users;

use App\FuelHistory;
use App\PhaseCode;
use App\RQ;
use App\ProjectCode;
use App\Downtime;
use App\Mail\FuelUnavailable;
use Illuminate\Support\Facades\Mail;
use App\DPortalUser;
use App\DPortalApp;
use App\DPortalUserAccess;

class MaintenanceController extends Controller
{    

    public function location_save_to_session(Request $request){
        session(['location_gasman' => $request->location_gasman]);
        return back()->with('success', 'Successfully updated location!');
    }
    public function rq_index(){
        $rqs = RQ::where('status','VERIFIED')->orderBy('created_at','desc');

        if(isset($_GET['rq'])){
            $rqs = $rqs->where('rq_no','LIKE','%'.$_GET['rq'].'%');
        }

        if(!empty($_GET['dept'])){
            $rqs = $rqs->where('dept',$_GET['dept']);
        }

        if(!empty($_GET['costcode'])){
            $rqs = $rqs->where('costcode','LIKE','%'.$_GET['costcode'].'%');
        }

        if(!empty($_GET['start_date'])){
            $rqs = $rqs->where('cutoff','>=',$_GET['start_date']);
        }

        if(!empty($_GET['end_date'])){
            $rqs = $rqs->where('cutoff','<=',$_GET['end_date']);
        }


        $rqs = $rqs->paginate(10);
        $depts = RQ::distinct('dept')->select('dept')->orderBy('dept')->get();

        return view('maintenance.rq',compact('rqs','depts'));
    }

    public function rq_pending(){

        $rqs = RQ::where('status','PENDING')->orderBy('created_at','asc');

        if(isset($_GET['rq'])){
            $rqs = $rqs->where('rq_no','LIKE','%'.$_GET['rq'].'%');
        }

        if(!empty($_GET['dept'])){
            $rqs = $rqs->where('dept',$_GET['dept']);
        }

        if(!empty($_GET['costcode'])){
            $rqs = $rqs->where('costcode','LIKE','%'.$_GET['costcode'].'%');
        }

        if(!empty($_GET['start_date'])){
            $rqs = $rqs->where('cutoff','>=',$_GET['start_date']);
        }

        if(!empty($_GET['end_date'])){
            $rqs = $rqs->where('cutoff','<=',$_GET['end_date']);
        }


        $rqs = $rqs->get();
        $depts = RQ::distinct('dept')->select('dept')->orderBy('dept')->get();

        return view('maintenance.rq-pending',compact('rqs','depts'));
    }

    public function rq_unverified(){
        $rqs = RQ::where('status','UNVERIFIED');

        if(isset($_GET['rq'])){
            $rqs = $rqs->where('rq_no','LIKE','%'.$_GET['rq'].'%');
        }

        if(!empty($_GET['dept'])){
            $rqs = $rqs->where('dept',$_GET['dept']);
        }

        if(!empty($_GET['costcode'])){
            $rqs = $rqs->where('costcode','LIKE','%'.$_GET['costcode'].'%');
        }

        if(!empty($_GET['start_date'])){
            $rqs = $rqs->where('cutoff','>=',$_GET['start_date']);
        }

        if(!empty($_GET['end_date'])){
            $rqs = $rqs->where('cutoff','<=',$_GET['end_date']);
        }


        $rqs = $rqs->paginate(10);
        $depts = RQ::distinct('dept')->select('dept')->orderBy('dept')->get();

        return view('maintenance.rq-unverified',compact('rqs','depts'));
    }

    public function rq_verification(Request $req)
    {
        $rq = RQ::find($req->rq_id);
        $rq->update([
            'status' => $req->status,
            'verified_by' => auth()->user()->domainAccount,
            'verified_at' => Carbon::now(),
            'remarks' => $req->reason
        ]);

        return redirect(route('rq.list'))->with('successMsg',' RQ verification request has been updated.');
    }

    // User Controller
    public function user(){
        $users = Users::where('role','<>','super admin')->paginate(10);
        $dportal_users = DPortalUser::where('role','<>','admin')->paginate(10);

        return view('maintenance.user',compact('users', 'dportal_users'));
    }

    public function userDportal(){
        $users = DPortalUser::where('role','<>','admin')->paginate(10);

        return view('maintenance.user-dportal',compact('users'));
    }

    public function user_create()
    {
        return view('maintenance.user-create');
    }

    public function user_store(Request $req)
    {

        $user = explode(' : ',$req->employee_data);

        if($req->role == 'department secretary') {
            $d_user = DPortalUser::create([
                'domain'    => $req->domain , 
                'password'  => \Hash::make('password') , 
                'fullname'  => $user[0] ,
                'dept_name' => $user[1] ,
                'dept_id'   => $user[3] ,
                'role'      => 'READ WRITE' , 
                'isActive'  => 1 ,
                'isICT'     => 0 ,
                'employee_id'   => $user[2]
            ]);

            $d_u_access = new DPortalUserAccess;
            $d_u_access->user_id = $d_user->id;
            $d_u_access->app_id  = 1;
            $d_u_access->status  = 'ACTIVE';
            $d_u_access->save();
            
        } else {
            Users::create([
                'name' => $user[0],
                'domainAccount' => $req->domain,
                'password' => \Hash::make('password'),
                'role' => $req->role,
                'dept' => $user[1],
                'isActive' => 1,
                'remember_token' => str_random(10),
                'created_at' => Carbon::now()
            ]);
        }

        return redirect(route('users'))->with('successMsg','User has been added successfully.');
    }

    public function user_delete(Request $req)
    {   
        if($req->has('is_dportal')) {
            DPortalUser::find($req->uid)->delete();
            DPortalUserAccess::where('user_id', $req->uid)->delete();
        } else {
            Users::find($req->uid)->delete();
        }

        return back()->with('successMsg', 'User has been deleted successfully.');
    }

    public function user_profile($id){
        $user = Users::where('id','=',$id)->first();

        return view('maintenance.user-info',compact('user'));
    }

    public function user_edit($id)
    {
        $user = Users::find($id);

        return view('maintenance.user-edit',compact('user'));
    }

    public function user_dportal_edit($id)
    {
        $user = DPortalUser::find($id);

        return view('maintenance.user-dportal-edit',compact('user'));
    }

    public function user_update(Request $req){

        $user = '';

        if($req->has('is_dportal')) {

            $user = DPortalUser::find($req->id);
            $user->domain = $req->domain;
            $user->save();

        } else {

            $user = Users::find($req->id);
            $user->role = $req->role;
            $user->domainAccount = $req->domain;
            $user->save();
        }

        if(isset($req->pword_reset) ){
            $user->update([
                'password' => \Hash::make('password')
            ]); 
        }

        return redirect(route('users'))->with('successMsg','&nbsp;  User has been updated successfully.');
    }

//


// Fuel Controller
    public function fuel_index(){
        $datas = Fuels::orderBy('id','desc')->paginate(10);

        return view('maintenance.fuel',compact('datas'));
    }

    public function fuel_store(Request $req){
        Fuels::create([
            'fuel_type'  => $req->description,
            'stock_code' => $req->stock_code,
            'cost' => $req->cost,
            'isActive'   => 1
        ]);

        return back()->with('successMsg','&nbsp;  Fuel type successfully created.');
    }

    public function fuel_update(Request $req){
        $qry = Fuels::where('id',$req->fuel_id);
        $data = $qry->first();

        $qry->update([
            'fuel_type' => $req->description,
            'stock_code'=> $req->stock_code,
            'cost' => $req->cost
        ]);

        if($data->cost <> $req->cost){
            FuelHistory::create([
                'fuel_id' => $req->fuel_id,
                'cost' => $req->cost,
                'updated_at' => Carbon::now(),
                'updated_by' => auth()->user()->domainAccount
            ]);
        }

        return back()->with('successMsg','&nbsp;  Fuel type details has been updated.');
    }

    public function delete(Request $req)
    {
        //$fuel = Fuels::find($req->fuel_id)->delete();
        $fuel_update = Fuels::whereId($req->fuel_id)->update(['isActive' => '0']);
        $fuel = Fuels::whereId($req->fuel_id)->first();


        Mail::to(env('EVERYONE_EMAIL'))->send(new FuelUnavailable($fuel, $req));

        return back()->with('successMsg','&nbsp;  Fuel type has been deleted permanently.');
    }

    public function enable(Request $req)
    {
     
        $fuel_update = Fuels::whereId($req->fuel_id)->update(['isActive' => '1']);       

        return back()->with('successMsg','&nbsp;  Fuel type has been enabled.');
    }
//



// Phase Code Routes
    public function phaseCode(){
        $codes = PhaseCode::orderBy('phase_desc','asc')->paginate(10);

        return view('maintenance.phase_code',compact('codes'));
    }

    public function phasecode_update(Request $req){
        PhaseCode::find($req->dept_id)->update([
            'phase_code' => $req->dept_code,
            'phase_desc' => $req->description
        ]);

        return back()->with('successMsg','&nbsp; Phase code has been updated.');
    }
//
    
    public function change_password(Request $req){
        $user = Users::find(auth()->user()->id)->update([
            'password' => \Hash::make($req->pword, array('rounds'=>12))
        ]);

        if($user){
            return redirect(route('logout'))->with('successMsg', 'Password successfully change. To login again, please use your new password!');
        } else {
            return back()->with('errorMsg', 'Password has not been changed.');
        }
    }

    public function projectCode(){
        $codes = ProjectCode::paginate(10);

        return view('maintenance.project_code',compact('codes'));
    }

    public function project_code_update(Request $req){
        ProjectCode::find($req->projectid)->update([
            'project_code' => $req->projectcode,
            'project_desc' => $req->pdesc,
            'phase_code' => $req->phasecode
        ]);

        return back()->with('successMsg','Project code has been updated.');
    }

    public function projectcode_delete(Request $req)
    {
        ProjectCode::find($req->projectid)->delete();

        return back()->with('successMsg','Porject code has been deleted permanently.');
    }
    
    public function downtime()
    {   
        $downtime = Downtime::orderBy('id','desc')->paginate(10);

        return view('maintenance.downtime',compact('downtime'));
    }

    public function downtime_store(Request $req)
    {
        $date = explode('-',$req->daterange);

        Downtime::create([
            'startdate' => $date[0],
            'enddate' => $date[1],
            'reason' => $req->reason,
            'status' => $req->status,
            'encoded_by' => auth()->user()->domainAccount
        ]);

        return redirect(route('downtime.list'))->with('successMsg','Downtime has been added.');
    }

    public function downtime_edit($id)
    {
        $d = Downtime::find($id);

        return view('maintenance.downtime-edit',compact('d'));

    }

    public function downtime_update(Request $req)
    {
        $date = explode('-',$req->daterange);

        Downtime::find($req->id)->update([
            'startdate' => $date[0],
            'enddate' => $date[1],
            'reason' => $req->reason,
            'status' => $req->status
        ]);

        return redirect(route('downtime.list'))->with('successMsg','Downtime has been updated.'); 
    }
}
