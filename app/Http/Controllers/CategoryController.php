<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function index()
    {
        return view('Pages.category.category');
    }
    public function readall()  //list all category
    {
        $categories = Cache::get('categories', function () {
            return Category::query()->get()
            ->map(function ($category) {
                $category->background = $category->background;
                return $category;
            });
        });

        return response()->json(['data' => $categories]);
    } 
      public function insert(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        $english_name=$validated['english_name'];     
         $file1 = $request->file('background');
        $destinationPath1 = public_path('categorybackground');
        $filepath1 = time() . $file1->getClientOriginalName();
        $file1->move($destinationPath1, $filepath1);
        $created_at= date('Y-m-d H:i:s');
        Category::create([
            'created_at'   => $created_at,
            'english_name' => $english_name,
            'logo'         => '',
            'background'   => 'public/categorybackground/' . $filepath1,
        ]);

        return redirect()->back();
    }
    public function delete(Request $request)
    {
        $id = $request->input('id');
        $category = Category::findOrFail($id);
        $path1 = $category->background;
        $category->delete();

        return redirect()->back();
    }
    public function getcategory($id) // to show customer details
    {
        $category = Category::findOrFail($id);
        return response()->json(['data' => [$category]]);
    }
    public function edit(UpdateCategoryRequest $request)
    {
        $id = $request->input('Eid');
        $background_name = $request->input('Ebackground_name');
        $english_name = $request->input('Eenglish_name');
        $file = $request->file('Ebackground');
        $updated_at = date('Y-m-d H:i:s');
        $category = Category::findOrFail($id);

        if ($file !== null) {
            $destinationPath = public_path('categorybackground');
            $filepath = time() . $file->getClientOriginalName();
            $file->move($destinationPath, $filepath);

            $category->update([
                'updated_at'   => $updated_at,
                'background'   => 'public/categorybackground/' . $filepath,
                'english_name' => $english_name,
            ]);
        } else {
            $category->update([
                'updated_at'   => $updated_at,
                'english_name' => $english_name,
            ]);
        }

        return redirect()->back();
    }
}
