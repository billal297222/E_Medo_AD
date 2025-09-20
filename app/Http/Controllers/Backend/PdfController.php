<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pdf;
use App\Models\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function index()
    {
         $pdfs = Pdf::with('date')->latest()->get();
        //  return view('backend.layouts.pdf.index', $data);
        return view('backend.layouts.pdf.index', compact('pdfs'));
    }

    // public function create()
    // {
    //     return view('backend.layouts.pdf.');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'short_desc' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf',
        ]);

        // Get today's date or create it if not exists
        $today = now()->toDateString();
        $date = Date::firstOrCreate(['date_value' => $today]);

        // Handle file upload to public/File
        $path = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('File'), $filename);
            $path = 'File/' . $filename;
        }

        Pdf::create([
            'date_id' => $date->id,
            'title' => $request->title,
            'short_desc' => $request->short_desc,
            'file_path' => $path,
        ]);

        return redirect()->route('pdf.index')->with('success', 'PDF uploaded successfully.');
    }

    public function edit($id)
    {
        $pdf = Pdf::findOrFail($id);
        return view('backend.layouts.pdf.edit', compact('pdf'));
    }

    public function update(Request $request, $id)
    {
        $pdf = Pdf::findOrFail($id);

        $request->validate([
            'title' => 'nullable|string|max:200',
            'short_desc' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf',
        ]);

        if ($request->filled('title')) $pdf->title = $request->title;
        if ($request->filled('short_desc')) $pdf->short_desc = $request->short_desc;

        if ($request->hasFile('file')) {
            if ($pdf->file_path && file_exists(public_path($pdf->file_path))) {
                unlink(public_path($pdf->file_path));
            }
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('File'), $filename);
            $pdf->file_path = 'File/' . $filename;
        }

        $pdf->save();

        return redirect()->route('pdf.index')->with('success', 'PDF updated successfully.');
    }

    public function destroy($id)
    {
        $pdf = Pdf::findOrFail($id);

        if ($pdf->file_path && file_exists(public_path($pdf->file_path))) {
            unlink(public_path($pdf->file_path));
        }

        $pdf->delete();

        return redirect()->route('pdf.index')->with('success', 'PDF deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        if ($request->ids) {
            foreach ($request->ids as $id) {
                $pdf = Pdf::find($id);
                if ($pdf) {
                    if ($pdf->file_path && file_exists(public_path($pdf->file_path))) {
                        unlink(public_path($pdf->file_path));
                    }
                    $pdf->delete();
                }
            }
        }
        return redirect()->route('pdf.index')->with('success', 'Selected PDFs deleted successfully.');
    }
}
