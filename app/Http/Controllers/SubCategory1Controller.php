<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SubCategory1;

class SubCategory1Controller extends Controller
{
    public function getscat($name)
    {
        $count = SubCategory1::where('english_name', $name)->count();
        return response()->json(['data' => $count]);
    }
    public function index()
    {
        return view('Pages.sub_category1.sub_category');
    }
    public function readall()  //list all sub_category
    {
        $categories = SubCategory1::select('id', 'english_name', 'background')->get()
            ->map(fn ($category) => [
                'id'           => $category->id,
                'english_name' => $category->english_name,
                'background'   => $category->background,
            ]);
        return response()->json(['data' => $categories]);
    } 
    public function readall_category($id)  //list all sub_category
    {
        $categories = SubCategory1::whereHas('parentSubCategories', function ($q) use ($id) {
            $q->where('sub_category.id', $id);
        })->select('id', 'english_name', 'background')->get()
        ->map(fn ($category) => [
            'id'           => $category->id,
            'english_name' => $category->english_name,
            'background'   => $category->background,
        ]);
        return response()->json(['data' => $categories]);
    } 
    public function categorysub_category($id)
    {
        return view('Pages.sub_category1.subcategory')->with('cid',$id);

    }
      public function insert(Request $request)
    {
        $english_name=$request->input('name');
        $parent_id=$request->input('cat_id');
        $file1 = $request->file('background');
        $destinationPath1 = public_path('categorybackground');
        $mdate = date("m/d/Y",time());
        $mdate1 = strtotime($mdate);  
        $filepath1= $mdate1.$file1->getClientOriginalName();
        $file1->move($destinationPath1, $filepath1);
        $subCategory1 = SubCategory1::create([
            'english_name' => $english_name,
            'background'   => 'public/categorybackground/' . $filepath1,
        ]);

        $subCategory1->parentSubCategories()->attach($parent_id);
     return redirect()->back();
    }
    public function delete(Request $request)
    {
        $id           = $request->input('id');
        $subCategory1 = SubCategory1::findOrFail($id);
        $path1        = $subCategory1->background;
        $subCategory1->delete();

        return redirect()->back();
    }
    public function getsub_category($id) // to show customer details
    {
        $subCategory1 = SubCategory1::select('id', 'english_name', 'background')->findOrFail($id);
        $parentIds    = $subCategory1->parentSubCategories()->pluck('sub_category.id');
        $subCategory1->sub = $parentIds->reduce(fn ($carry, $catId) => $carry . ',' . $catId, '');
        return response()->json(['data' => $subCategory1]);
    }
    public function edit(Request $request)
    {
        $id           = $request->input('Eid');
        $english_name = $request->input('Ename');
        $parent_id    = $request->input('Ecat_id');
        $file1        = $request->file('Ebackground');

        $subCategory1 = SubCategory1::findOrFail($id);
        $data         = ['english_name' => $english_name];

        if ($file1 !== null) {
            $destinationPath1   = public_path('categorybackground');
            $mdate1             = strtotime(date("m/d/Y", time()));
            $filepath1          = $mdate1 . $file1->getClientOriginalName();
            $file1->move($destinationPath1, $filepath1);
            $data['background'] = 'public/categorybackground/' . $filepath1;
        }

        $subCategory1->update($data);
        $subCategory1->parentSubCategories()->sync($parent_id);

        return redirect()->back();
    }
    
}
