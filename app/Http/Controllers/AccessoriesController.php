<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Models\Accessory;
use Illuminate\Support\Facades\Cache;

class AccessoriesController extends Controller
{
    public function index()
    {
        return view('Pages.accessories.list');
    }
    public function readall()  //list all slider
    {
       $acc = Cache::get('accessories', function () {
           return Accessory::select('id', 'photo', 'name', 'link')->get()
           ->map(function ($acc) {
               $acc->photo = $acc->photo;
               return $acc;
           });
       });
       return response()->json(['data' => $acc]);
    } 
      public function insert(Request $request)
    {
        $name=$request->input('name');
        $link=$request->input('link');
        $file = $request->file('image');
        $destinationPath = public_path('accessoriesuploads');
        $filepath = time() . $file->getClientOriginalName();
        $file->move($destinationPath, $filepath);
        Accessory::create([
            'photo' => 'public/accessoriesuploads/' . $filepath,
            'name'  => $name,
            'link'  => $link,
        ]);
     return redirect()->back();
    }
    public function delete(Request $request)
    {
    $id = $request->input('id');
    $acc = Accessory::findOrFail($id);
    $path = $acc->photo;
    $acc->delete();
    return redirect()->back();
    }
     public function getacc ($id) // to show customer details
    {
        $acc = Accessory::findOrFail($id);
        return response()->json(['data' => [$acc]]);
    }
      public function edit(Request $request)
    {
        $id=$request->input('Eid');
        $logo_name=$request->input('Elogo_name');
        $name=$request->input('Ename');
        $link=$request->input('Elink');
        $file = $request->file('Eimage');
        $updated_at= date('Y-m-d H:i:s');
        $acc = Accessory::findOrFail($id);
        if ($file != null) {
            $destinationPath = public_path('accessoriesuploads');
            $filepath = time() . $file->getClientOriginalName();
            $file->move($destinationPath, $filepath);
            $acc->update([
                'photo' => 'public/accessoriesuploads/' . $filepath,
                'name'  => $name,
                'link'  => $link,
            ]);
        } else {
            $acc->update([
                'name' => $name,
                'link' => $link,
            ]);
        }
    return redirect()->back();
    }
}
