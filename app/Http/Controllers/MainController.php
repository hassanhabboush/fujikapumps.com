<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use Carbon\Carbon;
use DateTime;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MainController extends Controller
{
    function index()
    {
        // User::where('email', 'fujika@admin.com')->update(['password' => bcrypt('password')]);
        return view('login');
    }
    function checklogin(Request $request)
    {
     $this->validate($request, [
      'email'   => 'required|email',
      'password'  => 'required|string'
     ]);

     $userdata = array(
      'email'  => $request->get('email'),
      'password' => $request->get('password')
     );
     
     if(Auth::attempt($userdata))
     {
   if(Auth::user()->active==1)
   {
     return redirect()->route('admin.category');
   }
   else
   {
      return back()->with('error', 'Account Inactive'); 
   }
      }
     else
     {
       return back()->with('error', 'error username or password '); 

     }

    }

    function successlogin()
    {
     return view('successlogin');
    }

    function logout()
    {
     Auth::logout();
     return redirect('login');
    }

}