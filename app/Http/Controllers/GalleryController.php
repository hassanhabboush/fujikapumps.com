<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Models\ProductGallery;
use Session;
use Illuminate\Support\Facades\Cache;

class GalleryController extends Controller
{
    public function index($id)
    {
      Session::put('product_id', $id);

        return view('Pages.product.gallery.gallery')->with('id',$id);

    }
    public function readall($id)  //list all slider
    {
       $source = Cache::get('gallery_'.$id, function () use ($id) {
           return ProductGallery::select('id', 'path')->where('product_id', $id)->get()
           ->map(function ($gallery) {
               $gallery->path = $gallery->path;
               return $gallery;
           });
       });
       return response()->json(['data' => $source]);
    } 
    public function add_gallery($product_id,$photo)
    {
        ProductGallery::create([
            'product_id' => $product_id,
            'path'       => $photo,
        ]);
    }
      public function insert(Request $request)
    {
        $file1 = $request->file('background');
        $product_id=session('product_id');
        $destinationPath1 = public_path('productimage');
        $filepath1 = time() . $file1->getClientOriginalName();
        $file1->move($destinationPath1, $filepath1);
        $this->add_gallery($product_id,'public/productimage/'.$filepath1);
        return redirect()->back();
    }
    public function delete(Request $request)
    {
    $id = $request->input('id');
    ProductGallery::findOrFail($id)->delete();
    return redirect()->back();
    }
}
