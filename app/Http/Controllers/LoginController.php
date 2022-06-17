<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use Illuminate\Contracts\Session\Session;
// use Session;

class LoginController extends Controller
{
    function index()
    {
        return view('login');
    }

    function checklogin(Request $request)
    {
        $this->validate($request, [
            'username'   => 'required',
            'password'  => 'required|alphaNum|min:3'
        ]);

        $user_data = array(
            'username'  => $request->get('username'),
            'password' => $request->get('password')
        );

        if(Auth::attempt($user_data))
        {
            session()->put('user', $request->get('username'));
            return redirect('/customer');
        }
        else
        {
            return back()->with('error', 'Wrong Login Details');
        }
    }
}
