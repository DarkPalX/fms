<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use Auth;
use Session;

class LoginController extends Controller
{
    
    public function login()
    {
    	return view('auth.login');
    }

    public function checklogin(Request $request)
    {
    	$this->validate($request, [
    		'domainAccount' 	=> 'required|string',
    		'password' 	=> 'required|alphaNum|min:4'
    	]);

        $user_data = array(
            'domainAccount' => $request->get('domainAccount'),
            'password'      => $request->get('password')
        );
        
    	if(Auth::attempt($user_data))
    	{	
            if(auth()->user()->role == 'gas man'){

                return redirect(route('fuel.pending-request',['location' => '0']));

            } elseif(auth()->user()->role == 'clerk') {

                return redirect(route('rq.list'));

            } else {

                return redirect(route('fuel.issuances'));

            }
            
    	}
    	else
    	{
            return back()->with('error','There was an error with your username / password combination. Please try again.');
    	   //return back ();
        }
    }

    public function logout()
    {
        Auth::logout();
        $msg = Session::get('successMsg');
        
        return redirect(route('login'))->with('msg', $msg);
    }
}
