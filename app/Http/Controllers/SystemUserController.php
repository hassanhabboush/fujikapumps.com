<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Models\User;
use Hash;
use Session;
class SystemUserController extends Controller
{
    public function index()
    {
        
        return view('Pages.system_user.system_user');
    }
    public function readall()  //list all stores
    {
        $users = User::select('id', 'name', 'email', 'created_at', 'updated_at', DB::raw("
  (CASE WHEN (role = 1) THEN 'Admin' WHEN (role=2) THEN 'User B' WHEN (role=3) THEN 'User C' Else 'User D' END) as role , (CASE WHEN (active =1 ) THEN 'active' ELSE 'Disactive' END ) as active"))->get();
       return response()->json(['data' => $users]);
    } 
      public function insert(Request $request)
    { 
       $this->validate($request, [
      'email' => 'required|string|email|max:255|unique:users',
   
      'name'=>'required',
      'role'=>'required'
     ]);
        
       User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>  Hash::make($request->password),
            'active'=>'0',
            'role'=> $request->role
        ]);
    
     return redirect()->back();
    }
    public function delete(Request $request)
    {
        $id = $request->input('id');
        User::findOrFail($id)->delete();
        return redirect()->back();
    }
      public function edit(Request $request)
    {
        $id = $request->input('Eid');
        $name = $request->input('Ename');
        $password = $request->input('Epassword');
        $role = $request->input('Erole');
        $email = $request->input('Eemail');
        $user = User::findOrFail($id);
        if ($password == Session::get('system_userpass')) {
            $user->update([
                'name'  => $name,
                'role'  => $role,
                'email' => $email,
            ]);
        } else {
            $user->update([
                'name'     => $name,
                'password' => Hash::make($password),
                'role'     => $role,
                'email'    => $email,
            ]);
        }
        
    return redirect()->back();
    }
    
     public function active_user($id) //active user
    {
        User::findOrFail($id)->update(['active' => 1]);
        return redirect()->back();
    }
      public function disactive_user($id) //disactive user
    {
        User::findOrFail($id)->update(['active' => 0]);
        return redirect()->back();
    }
      public function getsystem_user ($id) // to show customer details
    {
        $user = User::findOrFail($id);
        Session::put('system_userpass', $user->password);
        return response()->json(['data' => [$user]]);
    }
   /* public function readuserss()
    {
        
       $customers=DB::table('users')->select('email')->get();
       echo json_encode ($customers);
    }*/
    
}
