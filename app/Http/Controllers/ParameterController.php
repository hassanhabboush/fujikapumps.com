<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Models\ProductParameter;
use App\Models\ProductGallery;
use Session;

class ParameterController extends Controller
{
    public function index($id)
    {
      Session::put('product_id', $id);

        return view('Pages.product.parameter.parameter')->with('id',$id);

    }
    public function readall($id)  //list all slider
    {
       $source = ProductParameter::where('product_id', $id)->get();
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
      ProductParameter::create([
          'product_id'        => session('product_id'),
          'Model'             => $request['Model'],
          'SerialNumber'      => $request['SerialNumber'],
          'PowerKw'           => $request['PowerKw'],
          'PowerHp'           => $request['PowerHp'],
          'q'                 => $request['q'],
          'h'                 => $request['h'],
          'v'                 => $request['v'],
          'Discharge_diameter'=> $request['Discharge_diameter'],
          'Hertz'             => $request['Hertz'],
          'Material'          => $request['Material'],
          'RPM'               => $request['RPM'],
          'link'              => $request['link'],
      ]);

    }
    public function delete(Request $request)
    {
    $id = $request->input('id');
    ProductParameter::findOrFail($id)->delete();
    return redirect()->back();
    }
    public function update(Request $request)
    {
      ProductParameter::findOrFail($request['id'])->update([
          'Model'             => $request['Model'],
          'SerialNumber'      => $request['SerialNumber'],
          'PowerKw'           => $request['PowerKw'],
          'PowerHp'           => $request['PowerHp'],
          'q'                 => $request['q'],
          'h'                 => $request['h'],
          'v'                 => $request['v'],
          'Discharge_diameter'=> $request['Discharge_diameter'],
          'Hertz'             => $request['Hertz'],
          'Material'          => $request['Material'],
          'RPM'               => $request['RPM'],
          'link'              => $request['link'],
      ]);

    }
}
