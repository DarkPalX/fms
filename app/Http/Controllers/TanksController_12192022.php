<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tank;

class TanksController extends Controller
{

    public function __construct(){}


    public function index() {

        $tanks = Tank::paginate(20);

        return view('tank.index', compact('tanks'));

    }

    public function create() {

        return view('tank.create');

    }

    public function store(Request $request) {

        Tank::create($request->except('_token'));

        return redirect(route('tanks.index'))->with('successMsg',' Tank is successfuly created...');

    }

    public function edit($id) {

        $tank = Tank::find($id);

        return view('tank.edit', compact('tank'));

    }

    public function update($id, Request $request) {

        $tank = Tank::find($id);
        $tank->update($request->except('_token','_method'));

        return redirect(route('tanks.index'))->with('successMsg',' Tank is successfuly updated...');

    }

    public function tank_delete(Request $request) {

        $tank = Tank::find($request->uid);
        $tank->delete();

        return redirect(route('tanks.index'))->with('successMsg',' Tank is successfuly deleted...');

    }



}
