<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class CurrencyController extends Controller
{
    public function index()
    {
        return view('Pages.currency.currency');
    }
    public function readall()  //list all slider
    {
       $source = DB::table('currency')->select('id', 'name','iso','ex_price','created_at', 'updated_at')->get(); 
       return response()->json(['data' => $source]);
    } 
      public function insert(Request $request)
    {
        
        $created_at= date('Y-m-d H:i:s');
        $id = DB::table('currency')->insertGetId(
        [ 
            'created_at'=>$created_at,
            'name'=>$request->input('name') ?? null,
            'iso'=>$request->input('iso') ?? null,
            'ex_price'=>$request->input('ex_price') ?? null
        ]
       );
     return redirect()->back();
    }
    public function delete()
    {
    $id=$request->input('id') ?? null;
    DB::table('currency')->where('id', '=', $id)->delete();
    return redirect()->back();
    }
     public function getcurrency ($id) // to show customer details
    {
        $slider = DB::table('currency')->where('id','=',$id)->get(); 
        return response()->json(['data' => $slider]);
    }
      public function edit(Request $request)
    {
        $id=$request->input('Eid') ?? null;
        $name=$request->input('Ename') ?? null;
        $iso=$request->input('Eiso') ?? null;
        $ex_price=$request->input('Eex_price') ?? null;
        $updated_at= date('Y-m-d H:i:s');
        $id = DB::table('currency')->where('id', $id)
       ->update(
        [ 
            'updated_at'=>$updated_at,
             'name'=>$name,
             'iso'=>$iso,
             'ex_price'=>$ex_price
        ]
       );
    return redirect()->back();
    }
}
