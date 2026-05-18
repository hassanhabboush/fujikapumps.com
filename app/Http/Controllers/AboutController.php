<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\About;
use App\Models\Gallery;
use App\Models\Team;

class AboutController extends Controller
{
    public function index()
    {
        return view('Pages.about.about');
    }
     public function getabout () // to show customer details
    {
        $about = About::firstOrFail();
        return response()->json(['data' => $about]);
    }
    public function edit(Request $request)
    {
        $about = About::firstOrFail();

        $fields = [
            'linkyoutube' => $request->input('link'),
            'title1'      => $request->input('title1'),
            'title2'      => $request->input('title2'),
            'title3'      => $request->input('title3'),
            'title4'      => $request->input('title4'),
            'desc1'       => $request->input('desc1'),
            'desc2'       => $request->input('desc2'),
            'desc3'       => $request->input('desc3'),
            'desc4'       => $request->input('desc4'),
            'map'         => $request->input('map'),
        ];

        if ($file = $request->file('Eimage')) {
            $mdate           = strtotime(date('m/d/Y', time()));
            $filepath        = $mdate . $file->getClientOriginalName();
            $file->move(public_path('accessoriesuploads'), $filepath);
            $fields['photo'] = 'public/accessoriesuploads/' . $filepath;
        }

        $about->update($fields);

        return redirect()->back();
    }
     public function gallery()
    {

        return view('Pages.about.gallery.gallery');

    }
    public function readallgallery() //list all slider
    {
       $source = Gallery::select('id', 'path')->get();
       return response()->json(['data' => $source]);
    } 
    public function add_gallery($photo)
    {
        Gallery::create(['path' => $photo]);
    }
      public function insertgallery(Request $request)
    {
        $file1 = $request->file('background');
        $product_id=session('product_id');
        $destinationPath1 = public_path('gallery');
        $mdate = date("m/d/Y",time());
        $mdate1 = strtotime($mdate);  
        $filepath1= $mdate1.$file1->getClientOriginalName();
        $file1->move($destinationPath1, $filepath1);
        $this->add_gallery('public/gallery/'.$filepath1);
        return redirect()->back();
    }
    public function deletegallery(Request $request)
    {
    $id = $request->input('id');
    Gallery::findOrFail($id)->delete();
    return redirect()->back();
    }
     public function team()
    {
        return view('Pages.about.team.team');
    }
    public function readallteam() //list all slider
    {
       $source = Team::select('id', 'path')->get();
       return response()->json(['data' => $source]);
    } 
    public function add_team($photo)
    {
        Team::create(['path' => $photo]);
    }
      public function insertteam(Request $request)
    {
        $file1 = $request->file('background');
        $product_id=session('product_id');
        $destinationPath1 = public_path('team');
        $mdate = date("m/d/Y",time());
        $mdate1 = strtotime($mdate);  
        $filepath1= $mdate1.$file1->getClientOriginalName();
        $file1->move($destinationPath1, $filepath1);
        $this->add_team('public/team/'.$filepath1);
        return redirect()->back();
    }
    public function deleteteam(Request $request)
    {
    $id = $request->input('id');
    Team::findOrFail($id)->delete();
    return redirect()->back();
    }
}