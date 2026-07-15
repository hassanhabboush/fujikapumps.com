<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Session;
use Illuminate\Support\Facades\Mail;
use App\Models\Slider;
use App\Models\Family;
use App\Models\ProductParameter;
use App\Models\About;
use App\Models\Team;
use App\Models\Gallery;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubCategory1;
use App\Models\Series;
use App\Models\Product;
use App\Models\Accessory;
use App\Models\ProductGallery;


class WebsiteController extends Controller
{
    private const CACHE_DURATION = 3600; // 1 hour

    public function index()
    {
        $slider = Cache::remember('slider', self::CACHE_DURATION, fn() => Slider::all());
        $category= Cache::remember('category', self::CACHE_DURATION, fn() => Category::select('id', 'english_name', 'logo', 'background', 'created_at', 'updated_at')->get());
        $sub_category=Cache::remember('sub_category', self::CACHE_DURATION, fn() => SubCategory::select('id', 'english_name', 'background')->get());
        $sub_category1=Cache::remember('sub_category1', self::CACHE_DURATION, fn() => SubCategory1::select('id', 'english_name', 'background')->get());
        $family=Cache::remember('family', self::CACHE_DURATION, fn() => Family::select('id', 'english_name', 'background','link')->get());

        $productParams = Cache::remember('productParams', self::CACHE_DURATION, fn() => ProductParameter::select('Hertz', 'Discharge_diameter', 'Material', 'RPM', 'v')->get());
        $Hertz = $productParams->unique('Hertz')->filter(fn($item) => !empty($item['Hertz']))->values();
        $dm = $productParams->unique('Discharge_diameter')->filter(fn($item) => !empty($item['Discharge_diameter']))->values();
        $material= $productParams->unique('Material')->filter(fn($item) => !empty($item['Material']))->values(); 
        $rpm= $productParams->unique('RPM')->filter(fn($item) => !empty($item['RPM']))->values(); 
        $volt = $productParams->unique('v')->filter(fn($item) => !empty($item['v']))->values(); 
        $about = Cache::remember('about', self::CACHE_DURATION, fn() => About::get()); 

        $featured_product = Cache::remember('featured_product', self::CACHE_DURATION, fn() => Product::select('id','name', 'is_featured', 'descreption', 'photo','link')->where('is_featured',1)->get());

        return view('web.home')->with(["about"=>$about,"Hertz"=>$Hertz,"dm"=>$dm,"material"=>$material,"rpm"=>$rpm,"volt"=>$volt,"family"=>$family,"slider"=>$slider,"sub_category"=>$sub_category,"sub_category1"=>$sub_category1,"products"=>$featured_product,"category"=>$category]); 
    }
    public function about()
    { 
        $about = Cache::remember('about', self::CACHE_DURATION, fn() => About::get()); 
        $team = Cache::remember('team', self::CACHE_DURATION, fn() => Team::all());
        $gallery = Cache::remember('gallery', self::CACHE_DURATION, fn() => Gallery::all());
        $category= Cache::remember('category', self::CACHE_DURATION, fn() => Category::select('id', 'english_name', 'background', 'created_at', 'updated_at')->get());
    
        return view('web.about')->with(["category"=>$category,"about"=>$about,"gallery"=>$gallery,"team"=>$team]);  
    }
    public function contact()
    { 
        $category=Cache::remember('category', self::CACHE_DURATION, fn() => Category::select('id', 'english_name',  'background')->get());

        return view('web.contact')->with(["category"=>$category]);  
    }
    public function categories($id,$type,$name)
    {
        Session::put('type', $type);
         $category= Category::with('subCategories.subCategory1s.families')
         ->select('id', 'english_name', 'logo', 'background', 'created_at', 'updated_at', 'short_descreption')->paginate(9);
         $res;
         if ($type==1)
         {
          $res=$category;
          return view('web.category')->with(["category"=>$category,"res"=>$res,"type"=>$type]);
          
         }
         if ($type==2)
         {
          $res=Cache::remember('subcategories_type2_'.$id, self::CACHE_DURATION, function () use ($id) {
              return SubCategory1::whereHas('parentSubCategories', function ($q) use ($id) {
                  $q->whereHas('categories', function ($q2) use ($id) {
                      $q2->where('categories.id', $id);
                  });
              })->select('id', 'english_name', 'background')->get();
          });
       return view('web.category')->with(["category"=>$category,"res"=>$res,"type"=>$type]);
       
         }
         if ($type==3)
         {
          $subCategory=Cache::remember('subcategory_'.$id, self::CACHE_DURATION, fn() => SubCategory::findOrFail($id));
          $res=$subCategory->subCategory1s()
              ->select('sub_category_1.id', 'sub_category_1.english_name', 'sub_category_1.background')
              ->paginate(9);
          return view('web.category')->with(["category"=>$category,"res"=>$res,"type"=>$type]);
          
         }
         if ($type==4)
         {
          $res=Cache::remember('series_type4_'.$id, self::CACHE_DURATION, function () use ($id) {
              return Series::whereHas('family', function ($q) use ($id) {
                  $q->whereHas('subCategory1s', function ($q2) use ($id) {
                      $q2->where('sub_category_1.id', $id);
                  });
              })->select('id', 'link', 'english_name', 'photo', 'text1', 'text2', 'text3')->get();
          });
          return view('web.series')->with(["category"=>$category,"products"=>$res]);
          
         }
         if ($type==5)
         {
          $res=Cache::remember('series_type5_'.$id, self::CACHE_DURATION, fn() => Series::select('id', 'english_name','photo','link','text1','text2','text3')->where('family_id',$id)->paginate(9));  
          return view('web.series')->with(["category"=>$category,"products"=>$res]);
          
         }
          if ($type==7)
         {
          $res=Cache::remember('accessories', self::CACHE_DURATION, fn() => Accessory::select('id', 'name','photo','link')->paginate(15));  
          return view('web.Accessories')->with(["category"=>$category,"products"=>$res]);
          
         }
         if ($type==6)
         {
         
          $product=Cache::remember('product_type6_'.$id, self::CACHE_DURATION, fn() => Product::select('products.id',  'products.name', 'is_featured', 'products.photo','descreption','products.link')->where('id', $id)->get());
          $gallery=Cache::remember('product_gallery_type6_'.$id, self::CACHE_DURATION, fn() => ProductGallery::select('path')->where('product_id',$product[0]->id)->get());
          return view('web.product')->with(["product"=>$product,"category"=>$category,'gallery'=>$gallery]); 
         }
    }
    
    public function get_category()
    {
        $categories = Cache::remember('categories', self::CACHE_DURATION, fn() => Category::select('id','english_name','background')->get()); 
       return response()->json(['data' => $categories]);
    }
     public function get_volt()
    {
        $volt = Cache::remember('volt', self::CACHE_DURATION, fn() => ProductParameter::select('v')->distinct('v')->get()); 
       return response()->json(['data' => $volt]);
    }
     public function get_hertz()
    {
        $Hertz = Cache::remember('hertz', self::CACHE_DURATION, fn() => ProductParameter::select('Hertz')->distinct('Hertz')->get()); 
       return response()->json(['data' => $Hertz]);
    }
     public function get_dm()
    {
        $dm = Cache::remember('dm', self::CACHE_DURATION, fn() => ProductParameter::select('Discharge_diameter')->distinct('Discharge_diameter')->get()); 
       return response()->json(['data' => $dm]);
    }
    public function get_material()
    {
        $material= Cache::remember('material', self::CACHE_DURATION, fn() => ProductParameter::select('Material')->distinct('Material')->get()); 
       return response()->json(['data' => $material]);
    }
     public function get_rpm()
    {
        $rpm= Cache::remember('rpm', self::CACHE_DURATION, fn() => ProductParameter::select('RPM')->distinct('RPM')->get()); 
       return response()->json(['data' => $rpm]);
    }
public function send_email(Request $request){
$to_name = "Fujika Contact Form";
$to_email = $request->input('email');
$from_email = env('MAIL_FROM_ADDRESS', 'no-reply@fujikaindustries.com');
$from_name  = env('MAIL_FROM_NAME', 'Fujika Contact Form');
$body = 'Name:'    . ($request->input('name'))    . "\n"
      . 'Email:'   . (env('RECIEVER_EMAIL', 'Sales@Fujikapumps.com'))   . "\n"
      . 'Phone:'   . ($request->input('phone'))   . "\n"
      . 'Company:' . ($request->input('company')) . "\n"
      . 'Enquiry:' . ($request->input('inquiry'));
Mail::raw($body, function($message) use ($to_name, $to_email, $from_email, $from_name) {
$message->to($to_email, $to_name)
->subject("Fujika Contact Form")
->from($from_email, $from_name);
});
return redirect()->back();
    }
    
    
public function filterpop(Request $request)
{
   $keyword=$request->input('keyword');
   if ($request->has('commercial'))
   {
    $res=Cache::remember('filterpop_commercial_' . $keyword, self::CACHE_DURATION, fn() => Family::select('id', 'english_name as name', 'link')->where('english_name', 'like', '%' . $keyword . '%')->get());

   }
   else
   {
    $res=Cache::remember('filterpop_product_' . $keyword, self::CACHE_DURATION, fn() => Product::select('id','name','link')->where('name', 'like', '%' . $keyword . '%')->get());
 
   }
        $category=Cache::remember('categories', self::CACHE_DURATION, fn() => Category::select('id', 'english_name', 'background', 'created_at', 'updated_at')->get());
   return view('web.filterpop')->with(["category"=>$category,"res"=> $res]); ;
   
}
public function filter(Request $request)
    {
      $products=ProductParameter::select('Model', 'SerialNumber', 'q', 'h', 'PowerKw', 'PowerHp', 'v', 'Discharge_diameter', 'Hertz', 'link');

      if (request('cat_id'))
      {
          $products=$products->whereHas('product.family.subCategory1s.parentSubCategories.categories', function ($q) use ($request) {
              $q->where('categories.id', $request->input('cat_id'));
          });
          if (request('sub_cat_id'))
          {
             $products=$products->whereHas('product.family.subCategory1s.parentSubCategories', function ($q) use ($request) {
                 $q->where('sub_category.id', $request->input('sub_cat_id'));
             });
             if (request('sub_cat_id1'))
             {
                $products=$products->whereHas('product.family.subCategory1s', function ($q) use ($request) {
                    $q->where('sub_category_1.id', $request->input('sub_cat_id1'));
                });
                 if (request('family'))
                 {
                   $products=$products->whereHas('product', function ($q) use ($request) {
                       $q->where('family_id', $request->input('family'));
                   });
                 }
             }
          }
      }
        if (request('rpm') && request('rpm')!='RPM')
        {
            $products=$products->where('RPM',$request->input('rpm')); 
        }
        if (request('material')&&request('material')!='Material')
        {
          $products=$products->where('Material',$request->input('material'));   
        }
         if (request('dm') &&request('dm')!='Size'){
           $products=$products->where('Discharge_diameter',$request->input('dm'));   
  
         }
         
          if (request('hertz')&& request('hertz')!='Hertz'){
                       $products=$products->where('Hertz',$request->input('hertz'));   

         }
         if (request('volt')&&request('volt')!='Voltage'){
                       $products=$products->where('v',$request->input('volt'));   

         }
           if (request('q'))
           {
               if (request('q') < 20)
                   {
                   $minq=request('q')-((request('q')*50)/100);
                   $maxq=request('q')+((request('q')*50)/100);
                   }
           elseif (request('q') < 35)
                   {
                   $minq=request('q')-((request('q')*30)/100);
                   $maxq=request('q')+((request('q')*30)/100);
                   }
            elseif (request('q') <= 50)
                   {
                   $minq=request('q')-((request('q')*20)/100);
                   $maxq=request('q')+((request('q')*20)/100);
                   }
            elseif (request('q') > 50)
                    {
                    $minq=request('q')-((request('q')*10)/100);
                    $maxq=request('q')+((request('q')*10)/100);
                    }
                $products=$products->whereBetween('q', [$minq, $maxq]);
           }
        
             if (request('h'))
             {
                 if (request('h') < 20)
                 {
                    $minh=request('h')-((request('h')*50)/100);
                    $maxh=request('h')+((request('h')*50)/100); 
                 }
                 elseif (request('h') < 35)
                 {
                    $minh=request('h')-((request('h')*30)/100);
                    $maxh=request('h')+((request('h')*30)/100); 
                 }
                 elseif (request('h') <= 50)
                 {
                    $minh=request('h')-((request('h')*20)/100);
                    $maxh=request('h')+((request('h')*20)/100); 
                 }
                elseif (request('h') > 50)
                 {
                     $minh=request('h')-((request('h')*10)/100);
                     $maxh=request('h')+((request('h')*10)/100); 
                 }
               $products=$products->whereBetween('h', [$minh, $maxh]);
             }
        
             $productsarr = $products->get();

            $maxValues = ProductParameter::select('Model', DB::raw('MAX(q) as q'), DB::raw('MAX(h) as h'))
                ->whereIn('Model', $productsarr->pluck('Model')->unique()->all())
                ->groupBy('Model')
                ->get()
                ->keyBy('Model');

            foreach ($productsarr as $product) {
                $product->q = $maxValues[$product->Model]->q ?? null;
                $product->h = $maxValues[$product->Model]->h ?? null;
            }
             
        $category=Category::select('id', 'english_name', 'background', 'created_at', 'updated_at')->get();
 return view('web.filter')->with(["category"=>$category,"products"=> $productsarr]); 
        
    }

}