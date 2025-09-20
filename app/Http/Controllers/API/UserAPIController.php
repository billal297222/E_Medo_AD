<?php

namespace App\Http\Controllers\API;

use App\Models\Pdf;
use App\Models\Date;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDFGenerator;


class UserAPIController extends Controller
{
use ApiResponse;
    public function index()
{
    $pdfs = Pdf::with('date')
        ->latest()
        ->get()
        ->map(function ($pdf) {
            return [
                'date'       => $pdf->date->date_value ?? null,
                'created_time' => $pdf->created_at->format('H:i:s'),
                'title'      => $pdf->title,
                'pdf_url'    => url($pdf->file_path),
            ];
        });

        if ($pdfs->isEmpty()) {
        return $this->error('','No PDF found .',200);
      }

      return $this->success($pdfs,'pdf found in this days',201);
    }


public function getDaysByMonth(Request $request)
{
    $request->validate([
        'year' => 'required|integer|min:2000',
    ]);

    $month = $request->month;
    $year = $request->year;

    $daysWithPdf = Pdf::whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->pluck('created_at')
        // ->map(fn($date) => $date->format('Y-m-d'))
        ->map(fn($date) => (string) $date->format('Y-m-d'))
        ->unique()
        ->values();
    if ($daysWithPdf->isEmpty()) {
        return $this->error('','No PDF found for this month.',200);

    }
     return $this->success($daysWithPdf,'pdf found in this days',200);

}

//    public function summary()
// {

//     $today = now()->format('Y-m-d');


//     $dateRecord = Date::where('date_value', $today)->first();

//     if (!$dateRecord) {
//          return $this->error('','No PDF found for today.',200);

//     }

//     // Get PDFs for today only
//     $pdfs = Pdf::where('date_id', $dateRecord->id)
//         ->get(['title', 'short_desc'])
//         ->map(function ($pdf) use ($dateRecord) {
//             return [
//                 'date' => $dateRecord->date_value,
//                 'title' => $pdf->title,
//                 'short_desc' => $pdf->short_desc,
//             ];
//         });

//         return $this->success($pdfs,'Pdfs found for today',201);


// }


public function summary(Request $request, $page = 1)
{
    // $perPage = 10;
    $perPage = (int) $request->query('per_page', 10);

    $pdfs = Pdf::with('date:id,date_value')->orderBy('updated_at', 'desc')
           ->paginate($perPage, ['title', 'short_desc', 'date_id'], 'page', $page);

    $customResponse = [
        'current_page' => $pdfs->currentPage(),
        'data' => $pdfs->map(function($pdf) {
            return [
                'title' => $pdf->title,
                'short_desc' => $pdf->short_desc,
                'date_value' => $pdf->date->date_value ?? null,
            ];
        })->toArray(),
    ];

    return $this->success($customResponse, 'PDF summary', 200);
}


// public function downloadTodaySummary()
// {
//     $today = now()->format('Y-m-d');
//     $dateRecord = Date::where('date_value', $today)->first();

//     if (!$dateRecord) {
//          return $this->error('','No PDF summary found for today.',201);

//     }

//     $pdfs = Pdf::where('date_id', $dateRecord->id)
//         ->get(['title', 'short_desc']);



//     try {

//     $pdf = PDFGenerator::loadView('backend.layouts.pdf.downloadSummary', compact('pdfs', 'dateRecord'));

//     return $pdf->download("today_summary_{$today}.pdf");
//     // return $this->success($summary,'Summary download successfull',201);
// } catch (\Exception $e) {
//     return $this->error($e,'PDF generation failed',505);

// }

// }

// public function downloadSummary(Request $request, $page = 1)
// {
//     $perPage = 10;
//     $pdfs = Pdf::with('date:id,date_value')
//         ->orderBy('updated_at', 'desc')
//         ->paginate($perPage, ['id', 'title', 'short_desc', 'date_id'], 'page', $page);

//     if ($pdfs->isEmpty()) {
//         return $this->error('', "No PDFs found for page {$page}", 201);
//     }

//     try {
//         $pdf = PDFGenerator::loadView('backend.layouts.pdf.downloadSummary', [
//             'pdfs' => $pdfs->map(function($pdf) {
//                 return [
//                     'title' => $pdf->title,
//                     'short_desc' => $pdf->short_desc,
//                     'date_value' => $pdf->date->date_value ?? null,
//                 ];
//             })->toArray(),
//             'currentPage' => $pdfs->currentPage(),
//             'pageNumber' => $page,
//         ]);

//         return $pdf->download("summary_page_{$page}.pdf");
//     } catch (\Exception $e) {
//         return $this->error($e->getMessage(), 'PDF generation failed', 505);
//     }
// }


public function downloadSummary(Request $request)
{
    $page = (int) $request->query('page', 1);
    // $perPage = 10;
    $perPage = (int) $request->query('per_page', 10);


    $pdfs = Pdf::with('date:id,date_value')
        ->orderBy('updated_at', 'desc')
        ->paginate($perPage, ['id', 'title', 'short_desc', 'date_id'], 'page', $page);

    if ($pdfs->isEmpty()) {
        return $this->error('', "No PDFs found for page {$page}", 201);
    }

    try {
        $pdf = PDFGenerator::loadView('backend.layouts.pdf.downloadSummary', [
            'pdfs' => $pdfs->map(function($pdf) {
                return [
                    'title' => $pdf->title,
                    'short_desc' => $pdf->short_desc,
                    'date_value' => $pdf->date->date_value ?? null,
                ];
            })->toArray(),
            'currentPage' => $pdfs->currentPage(),
            'pageNumber' => $page,
        ]);

        return $pdf->download("summary_page_{$page}.pdf");
    } catch (\Exception $e) {
        return $this->error($e->getMessage(), 'PDF generation failed', 505);
    }
}




public function show($id)
{
    $pdf = Pdf::with('date')->find($id);

    if (!$pdf) {
        return $this->error('', 'No PDF found.', 404);
    }

    $data = [
        'date'         => $pdf->date->date_value ?? null,
        'created_time' => $pdf->created_at->format('H:i:s'),
        'title'        => $pdf->title,
        'short_desc'   => $pdf->short_desc,
        'pdf_url'      => url($pdf->file_path),
    ];

    return $this->success($data, 'Single PDF information', 200);
}


public function pdfsByDay($date)
{

    $dateRecord = Date::where('date_value', $date)->first();

    if (!$dateRecord) {
         return $this->error('','No PDFs found for this date',200);

    }

    // Get PDFs for this date
    $pdfs = Pdf::where('date_id', $dateRecord->id)
        ->get(['title', 'short_desc', 'file_path'])
        ->map(function($pdf) {
            return [
                'title' => $pdf->title,
                'short_desc' => $pdf->short_desc,
                'file_url' => url($pdf->file_path)
            ];
        });


         return $this->success($pdfs,'Pdfs found for today',201);

}


 // Download PDF
    public function downloadPdf($id)
    {
       $pdf = Pdf::find($id);

    if (!$pdf || !file_exists(public_path($pdf->file_path))) {
         return $this->error('','Pdf not found',404);
    }

    return response()->download(public_path($pdf->file_path), $pdf->title . '.pdf');
    }

    // Print PDF
    // public function printPdf($id)
    // {
    //    $pdf = Pdf::find($id);
    // if (!$pdf || !file_exists(public_path($pdf->file_path))) {
    //     return response()->json(['status' => 'error', 'message' => 'PDF not found'], 404);
    // }

    // return response()->json([
    //     'status' => 'success',
    //     'url' => asset($pdf->file_path)
    // ]);

    // }


    public function userInfo(Request $request)
    {
        $user = $request->user();

        if (!$user) {

            return $this->error('','Unauthenticated.',401);
        }

            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
            ];

            return $this->success($data,'user information',201);

    }


}
