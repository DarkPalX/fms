<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

use App\IssuanceRequest;
use App\ApprovalRequest;

class ReportController extends Controller
{

    public function status()
    {
        $qry = IssuanceRequest::whereNotNull('id');

        if(isset($_GET['status'])){
            $qry->where('status',$_GET['status']);
        } else {
            $qry->where('status','PENDING');
        }

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry->whereBetween('created_at',[$_GET['startdate'].' 00:00:00',$_GET['enddate'].' 23:59:59']);
        }

        $rs = $qry->orderBy('created_at','asc')->get();


        return view('reports.issuance-status',compact('rs'));
    }

    public function department()
    {
        $qry = IssuanceRequest::whereNotNull('id');

        if(isset($_GET['dept'])){
            $qry->where('dept',$_GET['dept'])->where('status',$_GET['status']);
        } else {
            $qry->where('status','ISSUED')->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()]); 
        }

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry->whereBetween('created_at',[$_GET['startdate'].' 00:00:00',$_GET['enddate'].' 23:59:59']);
        } else {
            $qry->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()]); 
        }


        $rs = $qry->orderBy('created_at','asc')->get();
        $depts = IssuanceRequest::distinct()->orderBy('dept','asc')->get(['dept']);

        return view('reports.issuance-department',compact('depts','rs'));
    }


    public function vehicle()
    {
        $qry = IssuanceRequest::whereNotNull('id');

        if(isset($_GET['vehicle'])){
            $qry->where('vehicle_id',$_GET['vehicle'])->where('status',$_GET['status']);
        } else {
            $qry->where('status','ISSUED')->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()]); 
        }      

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry->whereBetween('created_at',[$_GET['startdate'].' 00:00:00',$_GET['enddate'].' 23:59:59']);
        } else {
            $qry->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()]); 
        }

        $rs = $qry->orderBy('created_at','asc')->get();
        $vehicle = IssuanceRequest::distinct()->orderBy('vehicle','asc')->get(['vehicle','vehicle_id','plate_no']);

        return view('reports.issuance-vehicle',compact('vehicle','rs'));
    }

    public function issuances()
    {
        $qry = IssuanceRequest::whereNotNull('id');

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry->whereBetween('created_at',[$_GET['startdate'].' 00:00:00',$_GET['enddate'].' 23:59:59']);
        } else {
            $qry->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()]); 
        }

        $rs = $qry->orderBy('created_at','asc')->get();


        return view('reports.issuances',compact('rs'));
    }


}
