<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Models\Slider;

class SliderController extends Controller
{
    public function index()
    {
        return view('Pages.slider.slider');
    }
    public function readall()  //list all slider
    {
       $sliders = Slider::select('id', 'image', 'text1', 'text2', 'text3', 'buttontext', 'buttonlink')->get()
       ->map(function ($slider) {
           $slider->image = $slider->image;
           return $slider;
       });
       return response()->json(['data' => $sliders]);
    } 
      public function insert(Request $request)
    {
        $text1=$request->input('text');
        $text2=$request->input('text2');
        $text3=$request->input('text3');
        $button_text=$request->input('button_text');
        $buttonlink=$request->input('buttonlink');
        

        $file = $request->file('image');
        $destinationPath = public_path('slideruploads');
        $filepath = time() . $file->getClientOriginalName();
        $file->move($destinationPath, $filepath);
        Slider::create([
            'image'      => 'public/slideruploads/' . $filepath,
            'text1'      => $text1,
            'text2'      => $text2,
            'text3'      => $text3,
            'buttontext' => $button_text,
            'buttonlink' => $buttonlink,
        ]);
     return redirect()->back();
    }
    public function delete(Request $request)
    {
    $id = $request->input('id');
    $slider = Slider::findOrFail($id);
    $path = $slider->image;
    $slider->delete();
    return redirect()->back();
    }
     public function getslider ($id) // to show customer details
    {
        $slider = Slider::findOrFail($id);
        return response()->json(['data' => [$slider]]);
    }
      public function edit(Request $request)
    {
        $id=$request->input('Eid');
        $logo_name=$request->input('Elogo_name');
        $text1=$request->input('Etext1');
        $text2=$request->input('Etext2');
        $text3=$request->input('Etext3');
        $button_text=$request->input('Ebuttontext');
        $buttonlink=$request->input('Ebuttonlink');     
           $file = $request->file('Eimage');
        $updated_at= date('Y-m-d H:i:s');
        $slider = Slider::findOrFail($id);
        if ($file != null) {
            $destinationPath = public_path('slideruploads');
            $filepath = time() . $file->getClientOriginalName();
            $file->move($destinationPath, $filepath);
            $slider->update([
                'image'      => 'public/slideruploads/' . $filepath,
                'text1'      => $text1,
                'text2'      => $text2,
                'text3'      => $text3,
                'buttontext' => $button_text,
                'buttonlink' => $buttonlink,
            ]);
        } else {
            $slider->update([
                'text1'      => $text1,
                'text2'      => $text2,
                'text3'      => $text3,
                'buttontext' => $button_text,
                'buttonlink' => $buttonlink,
            ]);
        }
    return redirect()->back();
    }
}
