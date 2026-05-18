<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    public function index()
    {
        return view('Pages.sub_category.sub_category');
    }
    public function readall()  //list all sub_category
    {
        $categories = SubCategory::select('id', 'english_name', 'background')->get()
        ->map(fn($category) => [
            'id' => $category->id,
            'english_name' => $category->english_name,
            'background' => $category->background,
        ]);
        return response()->json(['data' => $categories]);
    } 
    public function readall_category($id)  //list all sub_category
    {
       $categories = SubCategory::whereHas('categories', function ($q) use ($id) {
           $q->where('categories.id', $id);
       })->select('id', 'english_name', 'background')->get()
       ->map(fn($category) => [
           'id' => $category->id,
           'english_name' => $category->english_name,
           'background' => $category->background,
       ]);
       return response()->json(['data' => $categories]);
    } 
    public function categorysub_category($id)
    {
        return view('Pages.sub_category.subcategory')->with('cid',$id);

    }
      public function insert(Request $request)
    {
        $english_name=$request->input('name');
        $parent_id=$request->input('cat_id');
        $file1 = $request->file('background');
        $destinationPath1 = public_path('categorybackground');
        $filepath1 = time() . $file1->getClientOriginalName();
        $file1->move($destinationPath1, $filepath1);
        $subCategory = SubCategory::create([
            'english_name' => $english_name,
            'background'   => 'public/categorybackground/' . $filepath1,
        ]);

        $subCategory->categories()->attach($parent_id);
     return redirect()->back();
    }
    public function delete(Request $request)
    {
        $id          = $request->input('id');
        $subCategory = SubCategory::findOrFail($id);
        $path1       = $subCategory->background;
        $subCategory->delete();

        return redirect()->back();
    }
     public function getsub_category($id) // to show customer details
    {
        $subCategory = SubCategory::select('id', 'english_name', 'background')->findOrFail($id);
        $categoryIds = $subCategory->categories()->pluck('categories.id');
        $subCategory->sub = $categoryIds->reduce(fn ($carry, $catId) => $carry . ',' . $catId, '');
        return response()->json(['data' => $subCategory]);
    }
    public function edit(Request $request)
    {
        $id           = $request->input('Eid');
        $english_name = $request->input('Ename');
        $parent_id    = $request->input('Ecat_id');
        $file1        = $request->file('Ebackground');

        $subCategory = SubCategory::findOrFail($id);
        $data        = ['english_name' => $english_name];

        if ($file1 !== null) {
            $destinationPath1   = public_path('categorybackground');
            $filepath1          = time() . $file1->getClientOriginalName();
            $file1->move($destinationPath1, $filepath1);
            $data['background'] = 'public/categorybackground/' . $filepath1;
        }

        $subCategory->update($data);
        $subCategory->categories()->sync($parent_id);

        return redirect()->back();
    }
}
