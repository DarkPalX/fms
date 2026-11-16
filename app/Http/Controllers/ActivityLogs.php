<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Logs;

class ActivityLogs extends Controller
{

    public function activities(){
        $logs  = Logs::orderBy('id','desc')->paginate(10);

        return view('reports.logs',compact('logs'));
    }

}
