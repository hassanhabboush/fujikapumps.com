<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Models\Series;

class SeriesController extends Controller
{
    public function index()
    {
        return view('Pages.series.list');
    }
    public function readall()  //list all slider
    {
       $acc= Series::select('id','photo','english_name','link','text1','text2','text3')->get()
       ->map(function ($acc) {
           $acc->photo = $acc->photo;
           return $acc;
       }); 
       return response()->json(['data' => $acc]);
    } 
      public function insert(Request $request)
    {
        $name=$request->input('name');
        $link=$request->input('link');
        $family=$request->input('cat_id');
        $text1=$request->input('text');
        $text2=$request->input('text2');
        $text3=$request->input('text3');
        $file = $request->file('image');
        $destinationPath = public_path('seriesuploads');
        $mdate = date("m/d/Y",time());
        $mdate = strtotime($mdate);  
        $filepath= $mdate.$file->getClientOriginalName();
        $file->move($destinationPath, $filepath);
        $created_at= date('Y-m-d H:i:s');
        $id = Series::create(
        [
            'photo'=>'public/seriesuploads/'.$filepath,
            'english_name'=>$name,
            'link'=>$link,
            'family_id'=>$family,
             'text1'=>$text1,
            'text2'=>$text2,
            'text3'=>$text3
            
        ]
       );
     return redirect()->back();
    }
    public function delete(Request $request)
    {
    $id = $request->input('id');
    $series = Series::findOrFail($id);
    $path = $series->photo;
    $series->delete();
    return redirect()->back();
    }
     public function getacc ($id) // to show customer details
    {
        $series = Series::findOrFail($id);
        return response()->json(['data' => [$series]]);
    }
      public function edit(Request $request)
    {
        $id=$request->input('Eid');
        $logo_name=$request->input('Elogo_name');
        $name=$request->input('Ename');
        $link=$request->input('Elink');
        $family=$request->input('Ecat_id');
            $text1=$request->input('Etext1');
        $text2=$request->input('Etext2');
        $text3=$request->input('Etext3');
        $file = $request->file('Eimage');
        $updated_at= date('Y-m-d H:i:s');
        $series = Series::findOrFail($id);
        if ($file != null) {
            $destinationPath = public_path('seriesuploads');
            $mdate = strtotime(date("m/d/Y", time()));
            $filepath = $mdate . $file->getClientOriginalName();
            $file->move($destinationPath, $filepath);
            $series->update([
                'photo'        => 'public/seriesuploads/' . $filepath,
                'english_name' => $name,
                'link'         => $link,
                'family_id'    => $family,
                'text1'        => $text1,
                'text2'        => $text2,
                'text3'        => $text3,
            ]);
        } else {
            $series->update([
                'english_name' => $name,
                'link'         => $link,
                'family_id'    => $family,
                'text1'        => $text1,
                'text2'        => $text2,
                'text3'        => $text3,
            ]);
        }
    return redirect()->back();
    }
}
