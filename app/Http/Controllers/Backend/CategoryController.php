<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // Get categories for existence check
    public function get(Request $request)
    {
        $query = Category::query();

        if (!empty($request->id)) {
            $query->where('id', '!=', $request->id);
        }
        if (!empty($request->name)) {
            $query->where('name', $request->name);
        }
        if (!empty($request->priority)) {
            $query->where('priority', $request->priority);
        }

        return response()->json($query->get());
    }

    // Update category priorities


    // Toggle category status
   public function status(Request $request,$id)
{
    $category =  Category::findOrFail($id);

    if ($category) {
        $category->status = !$category->status; // toggle true/false
        $category->save();
    }

     return redirect()->route('category.index')->with('success', 'Category toggle updated successfully');
}


    // Delete category
    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return back()->with('success', 'Deleted successfully');
        }
        return back()->with('error', 'Category not found');
    }

    // Index page
    public function index()
    {
        $categories = Category::orderBy('priority')->get();
        return view('backend.layouts.category.index', compact('categories'));
    }

    // Store new category
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'priority' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first())->withInput();
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('categories'), $filename);
            $image =  $filename;
        } else {
            $image = 'default.png';
        }
         
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'priority' => $request->priority,
            'image' => $image
           
        ]);

        
        return back()->with('success', 'Category created successfully');
    }

    // update category
    public function update(Request $request, $id)
{
    $category = Category::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255|unique:categories,name,' . $id, // allow same name for current category
        'priority' => 'nullable|numeric',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return back()->with('error', $validator->errors()->first())->withInput();
    }

    // Handle image upload
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('categories'), $filename);

        // Delete old image if not default
        if ($category->image && $category->image !== 'default.png' && file_exists(public_path('categories/' . $category->image))) {
            unlink(public_path('categories/' . $category->image));
        }

        $category->image = $filename;
    }

    // Update other fields
    $category->name = $request->name;
    $category->slug = Str::slug($request->name);
    $category->priority = $request->priority;

    $category->save();

    return redirect()->route('category.index')->with('success', 'Category updated successfully');
}



    public function edit($id)
    {
        $category =  Category::findOrFail($id);
        return view('backend.layouts.category.edit', compact('category'));
    }



//     //active
//  public function active($id)
//  {
//      $category =  Category::findOrFail($id);

//     if ($category) {
//         $category->status = !$category->status; // toggle true/false
//         $category->save();
//     }

//      return redirect()->route('category.index')->with('success', 'Category Status updated successfully');
// }
    // public function destroy($id)
    // {
    //     $category = Category::findOrFail($id);

    //     $category->delete();

    //     return redirect()->back()->with('success', 'Category deleted successfully');
    // }

    // Update category

}
