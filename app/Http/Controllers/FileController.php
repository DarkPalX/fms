<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Import\DeptImports;
use App\Import\PhaseCodeImports;
use App\Import\RQImports;
use App\Import\VehicleImports;

// new
use App\Import\ProjectCodeImport;

class FileController extends Controller
{
    public function import() 
    {
        Excel::import(new DeptImports,request()->file('file'));

        return back()->with('successMsg','Department budget has been uploaded.');
    }

    public function import_phase_code() 
    {
        Excel::import(new PhaseCodeImports,request()->file('file'));

        return back()->with('successMsg','Phase code has been uploaded.');
    }

    // new
    public function import_project_code()
    {
        Excel::import(new ProjectCodeImport,request()->file('file'));

        return back()->with('successMsg','Project codes has been uploaded.');
    }

    public function import_rq() 
    {
        Excel::import(new RQImports,request()->file('file'));

        return back()->with('successMsg','RQ`s has been uploaded.');
    }

    public function vehicle_import() 
    {
        Excel::import(new VehicleImports,request()->file('file'));

        return back()->with('successMsg','Vehicle has been uploaded.');
    }
    
}
