<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\UserProfile;


class UserController extends Controller
{
    public function index()
    {
        return view('Pages.user.user');
    }
    public function readall()  //list all user
    {
       $users = UserProfile::select('id', 'name', 'email', 'mobile', 'country_id', 'points', 'register_type', 'created_at', 'active_non')->get();
       return response()->json(['data' => $users]);
    }
     public function active($id)  //list all user
    {
       UserProfile::findOrFail($id)->update(['active_non' => '1']);
       return redirect()->back();
    } 
     public function disactive($id)  //list all user
    {
      UserProfile::findOrFail($id)->update(['active_non' => '0']);
      return redirect()->back();
    } 
     public function readone($id)  //list all user
    {
       $user = UserProfile::select('id', 'name', 'email', 'mobile', 'country_id', 'points', 'register_type', 'created_at', 'active_non')->findOrFail($id);
       return response()->json(['data' => [$user]]);
    } 
}
