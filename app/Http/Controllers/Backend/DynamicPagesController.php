<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\DynamicPage;

class DynamicPagesController extends Controller
{
    //
    public function index()
    {
        $data['dynamicpages'] = DynamicPage::all();
        return view('backend.layouts.dynamic_pages.index', $data);
    }
    public function create()
    {
        return view('backend.layouts.dynamic_pages.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'page_title' => 'required|string|max:255',
            'page_content' => 'required|string',
        ]);

        if ($validator->fails()) {

            return back()->with('error', $validator->errors()->first())->withInput();
        }

        try {
            DynamicPage::create($data);

    // Redirect to the dynamic pages index with success message
           return redirect()->route('dynamicpages.index')
                     ->with('success', 'Dynamic Page successfully created');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Toggle dynamic status
    public function status(Request $request,$id)
    {
        $dynamic =  DynamicPage::findOrFail($id);
        if ($dynamic) {
            $dynamic->status = $dynamic->status === 'active' ? 'inactive' : 'active';
            $dynamic->save();
        }
             return redirect()->route('dynamicpages.index')->with('success', 'Dynamic toggle updated successfully');
        // return response()->json(['success' => true, 'message' => 'Status updated']);
    }

    public function edit($id)
    {
        $dynamicpages =  DynamicPage::findOrFail($id);
        return view('backend.layouts.dynamic_pages.edit', compact('dynamicpages'));
    }

public function destroy($id)
{
    $delete = DynamicPage::find($id)->delete();

    if ($delete) {
        return back()->with('success', 'Deleted Successfully');
    } else {
        return back()->with('error', 'Try Again!');
    }
}



    public function update(Request $request, $id)
    {
        $data = $request->only(['page_title', 'page_content']);

        $validator = Validator::make($data, [
            'page_title' => 'required|string|max:255',
            'page_content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first())->withInput();
        }
        //  dd($data);
        try {
           
            DynamicPage::findOrFail($id)->update($data);
            return redirect()->route('dynamicpages.index')->with('success', 'Dynamicpages updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    // public function bulkDelete(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $result = DynamicPage::whereIn('id', $request->ids)->get();

    //         if ($result) {
    //             DynamicPage::destroy($request->ids);
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Pages deleted successfully',
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Pages not found',
    //             ]);
    //         }
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Something went wrong',
    //         ]);
    //     }
    // }

   public function bulkDelete(Request $request)
{
    $ids = $request->ids ?? [];

    if (empty($ids)) {
        return back()->with('error', 'No pages selected!');
    }

    $deleted = DynamicPage::whereIn('id', $ids)->delete();

    if ($deleted) {
        return back()->with('success', 'Selected pages deleted successfully!');
    } else {
        return back()->with('error', 'Pages not found or already deleted.');
    }
}



}
