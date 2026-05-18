<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\ProductParameter;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index()
    {
         $products= Cache::remember('products', 60, function () {
            return Product::select('id','name','photo')->get();
        });
        return view('Pages.product.product')->with('products', $products); 
    }
    public function productdetails($id)
    {
        $productDetails= Cache::remember('product_'.$id, 60, function () use ($id) {
            return Product::findOrFail($id);
        });
       
        return view('Pages.product.productdetails', [
            'productDetails' => $productDetails
        ]); 
        
    }
    public function readall()  //list all category
    {
       $products = Cache::remember('products_all', 60, function () {
           return Product::select('is_featured','products.id', 'products.photo','products.name','products.link')->get();
       })
       ->map(function ($product) {
           $product->photo = $product->photo;
           return $product;
       }); 
       return response()->json(['data' => $products]);
    } 
    public function readallfeature()  //list all category
    {
       $products = Cache::remember('products_featured', 60, function () {
           return Product::select('is_featured', 'id', 'photo', 'name', 'link')->where('is_featured', 1)->get();
       })
       ->map(function ($product) {
           $product->photo = $product->photo;
           return $product;
       }); 
       return response()->json(['data' => $products]);
    } 
    public function indexfeature()
    {
        return view('Pages.product.featureproduct'); 
    }
    public function categoryproduct($id)
    {
        return view('Pages.product.categoryproduct')->with('id',$id);
    }

    public function readcategoryproduct($id)
    {
       
        $products = Cache::remember('products_category_'.$id, 60, function () use ($id) {
            return Product::whereHas('categories', function ($q) use ($id) {
                $q->where('categories.id', $id);
            })->select('is_featured', 'id', 'photo', 'name', 'link')->get();
        });
       return response()->json(['data' => $products]);
    }

    public function subcategoryproduct($id)
    {
        return view('Pages.product.subcategoryproduct')->with('id',$id);
    }

    public function readsubcategoryproduct($id)
    {
        $products = Cache::remember('products_subcategory_'.$id, 60, function () use ($id) {
            return Product::whereHas('subCategories', function ($q) use ($id) {
                $q->where('sub_category.id', $id);
            })->select('is_featured', 'id', 'photo', 'name', 'link')->get();
        });
        return response()->json(['data' => $products]);
    }
    public function add_category($product_id, $category_id)
    {
        Product::findOrFail($product_id)->categories()->attach($category_id);
    }
    public function add_subcategory($product_id, $subcategory_id)
    {
        Product::findOrFail($product_id)->subCategories()->attach($subcategory_id);
    }
    public function add_gallery($product_id, $photo)
    {
        ProductGallery::create([
            'product_id' => $product_id,
            'path'       => $photo,
        ]);
    }
    public function add_parameter($product_id, $parameter1, $parameter2, $parameter3, $parameter4, $parameter5, $parameter6, $parameter7, $parameter8, $parameter9, $parameter11, $parameter12, $parameter13)
    {
        ProductParameter::create([
            'product_id'         => $product_id,
            'Model'              => $parameter1,
            'SerialNumber'       => $parameter2,
            'PowerKw'            => $parameter3,
            'PowerHp'            => $parameter4,
            'q'                  => $parameter5,
            'h'                  => $parameter6,
            'v'                  => $parameter7,
            'Discharge_diameter' => $parameter8,
            'Hertz'              => $parameter9,
            'Material'           => $parameter11,
            'RPM'                => $parameter12,
            'link'               => $parameter13,
        ]);
    }
    public function checkUploadedFileProperties($extension, $fileSize)
{
$valid_extension = array("csv", "xlsx"); //Only want csv and excel files
$maxFileSize = 2097152; // Uploaded file size limit is 2mb
if (in_array(strtolower($extension), $valid_extension)) {
if ($fileSize <= $maxFileSize) {
} else {
throw new \Exception('No file was uploaded', Response::HTTP_REQUEST_ENTITY_TOO_LARGE); //413 error
}
} else {
throw new \Exception('Invalid file extension', Response::HTTP_UNSUPPORTED_MEDIA_TYPE); //415 error
}
}
      public function insert(Request $request)
    {
        $english_name=$request->input('name');
        $shortdescreption=$request->input('shortdescreption');
        $file1 = $request->file('background');
        $link=$request->input('link');
        $destinationPath1 = public_path('productbackground');
        $filepath1 = time() . $file1->getClientOriginalName();
        $file1->move($destinationPath1, $filepath1);
        $category_id=$request->input('cat_id');
        $images = $request->file('images');
        $product = Product::create([
            'name'        => $english_name,
            'descreption' => $shortdescreption,
            'photo'       => 'public/productbackground/' . $filepath1,
            'is_featured' => 0,
            'family_id'   => $category_id,
            'link'        => $link,
        ]);
    if($images !=null)
    {
     foreach($images as $image)
     {
        $destinationPath1 = public_path('productimage');
        $filepath1 = time() . $image->getClientOriginalName();
        $image->move($destinationPath1, $filepath1);
        $this->add_gallery($product->id, 'public/productimage/'.$filepath1);
     }
    }
     //import CSV
      $file = $request->file('parameter');
if ($file) {
$filename = $file->getClientOriginalName();
$extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
$tempPath = $file->getRealPath();
$fileSize = $file->getSize(); //Get size of uploaded file in bytes
//Check for file extension and size
$this->checkUploadedFileProperties($extension, $fileSize);
//Where uploaded file will be stored on the server 
$location = public_path('productcsv');; //Created an "uploads" folder for that
// Upload file
$file->move($location, $filename);
// In case the uploaded file path is to be stored in the database 
$filepath ='public/productcsv/' . $filename;
// Reading file
$file = fopen($filepath, "r");
$importData_arr = array(); // Read through the file and store the contents as an array
$i = 0;
//Read the contents of the uploaded file 
while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
$num = count($filedata);
// Skip first row (Remove below comment if you want to skip the first row)
if ($i == 0) {
$i++;
continue;
}
for ($c = 0; $c < $num; $c++) {
    $importData_arr[$i][] = $filedata[$c];
    }
    $i++;
    }
    fclose($file); //Close after reading
    $j = 0;
    foreach ($importData_arr as $importData) {
        $j++;
        $this->add_parameter($product->id, $importData[0],$importData[1],$importData[2],$importData[3],$importData[4],$importData[5],$importData[6],$importData[7],$importData[8],$importData[9],$importData[10],$importData[11]);
    }
}
    return redirect()->route('product');
    }
    public function delete(Request $request)
    {
    $id = $request->input('id');
    Product::findOrFail($id)->delete();
    ProductGallery::where('product_id', $id)->delete();
    ProductParameter::where('product_id', $id)->delete();
    return redirect()->back();
    }
     public function getproduct ($id) // to show customer details
    {
        $product = Product::findOrFail($id);
        return response()->json(['data' => [$product]]);
    }
      public function edit(Request $request)
    {
        $id=$request->input('Eid');
        $background_name=$request->input('Elogo_name');
        $english_name=$request->input('Ename');
        $short_descreption=$request->input('Eshortdescreption');
        $link=$request->input('Elink');
        $category_id=$request->input('Ecat_id');
        $product = Product::findOrFail($id);
        $file1 = $request->file('Ebackground');
        if ($file1 != null) {
            $destinationPath1 = public_path('productbackground');
            $filepath1 = time() . $file1->getClientOriginalName();
            $file1->move($destinationPath1, $filepath1);
            $product->update([
                'name'        => $english_name,
                'descreption' => $short_descreption,
                'family_id'   => $category_id,
                'photo'       => 'public/productbackground/' . $filepath1,
                'link'        => $link,
            ]);
        } else {
            $product->update([
                'name'        => $english_name,
                'descreption' => $short_descreption,
                'family_id'   => $category_id,
                'link'        => $link,
            ]);
        }
        return redirect('product');

    }
public function add_product()
{
    return view('Pages.product.addproduct'); 

}
public function edit_product($id)
{
    $product = Product::findOrFail($id);
    return view('Pages.product.editproduct')->with('product',$product); 

}
    public function feature($id)
    {
        Product::findOrFail($id)?->update(['is_featured' => 1]);
        return redirect()->back();
    }
public function remove_feature($id)
{
  
    Product::findOrFail($id)?->update(['is_featured' => 0]);

     return redirect()->back();
}

    public function check_validity(Request $request, $card_number, $store_id)
    {
        $product = Product::where('card_number', $card_number)
            ->where('store_id', $store_id)
            ->first();

        echo json_encode(['valid' => $product ? 1 : 0]);
    }

    public function check_validity1(Request $request, $card_number, $store_id, $card_number1)
    {
        $products = Product::whereIn('card_number', [$card_number, $card_number1])
            ->where('store_id', $store_id)
            ->get();

        echo json_encode(['valid' => $products->count(), 'products' => $products]);
    }

}
