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
            ->orderBy('date_id', 'desc') // order by custom date_id
            ->get()
            ->map(function ($pdf) {
                return [
                    'date_id'      => $pdf->date_id,
                    'date'         => $pdf->date->date_value ?? null,
                    'created_time' => $pdf->created_at->format('H:i:s'),
                    'title'        => $pdf->title,
                    'pdf_url'      => url($pdf->file_path),
                ];
            });

        if ($pdfs->isEmpty()) {
            return $this->error('', 'No PDF found.', 200);
        }

        return $this->success($pdfs, 'Total PDF found', 201);
    }



    public function getDaysByMonth(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2000',
            'month' => 'required|integer|between:1,12',
        ]);

        $month = $request->month;
        $year = $request->year;

        // Get days based on custom date_value
        $daysWithPdf = Pdf::with('date')
            ->whereHas('date', function ($query) use ($year, $month) {
                $query->whereYear('date_value', $year)
                    ->whereMonth('date_value', $month);
            })
            ->get()
            ->pluck('date.date_value')
            ->unique()
            ->values();

        if ($daysWithPdf->isEmpty()) {
            return $this->error('', 'No PDF found for this month.', 200);
        }

        return $this->success($daysWithPdf, 'PDF found on these days', 200);
    }




public function summary(Request $request, $page = 1, $perPage = 10)
{
    $page = (int) $page;
    $perPage = (int) $perPage;

    // Paginate PDFs ordered by custom date
    $pdfs = Pdf::with('date:id,date_value')
        ->join('dates', 'pdfs.date_id', '=', 'dates.id')  // join to order by custom date
        ->orderBy('dates.date_value', 'desc')            // order by latest custom date
        ->select('pdfs.*')                               // keep Pdf fields
        ->paginate($perPage, ['title', 'short_desc', 'date_id'], 'page', $page);

    $total_pdf = $pdfs->total();
    $total_page = ceil($total_pdf / $perPage);

    $customResponse = [
        'current_page' => $pdfs->currentPage(),
        'total_page' => $total_page,
        'data' => $pdfs->map(function ($pdf) {
            return [
                'title' => $pdf->title,
                'short_desc' => $pdf->short_desc,
                'date_value' => $pdf->date->date_value ?? null,
            ];
        })->toArray(),
    ];

    return $this->success($customResponse, 'PDF summary', 200);
}


    public function downloadSummary(Request $request, $page = 1, $perPage = 10)
{
    $page = (int) $page;
    $perPage = (int) $perPage;

    // Paginate PDFs ordered by custom date
    $pdfs = Pdf::with('date:id,date_value')
        ->join('dates', 'pdfs.date_id', '=', 'dates.id') // join dates table
        ->orderBy('dates.date_value', 'desc')            // order by latest custom date
        ->select('pdfs.*')                               // select PDF fields
        ->paginate($perPage, ['id', 'title', 'short_desc', 'date_id'], 'page', $page);

    if ($pdfs->isEmpty()) {
        return $this->error('', "No PDFs found for page {$page}", 200);
    }

    try {
        $pdf = PDFGenerator::loadView('backend.layouts.pdf.downloadSummary', [
            'pdfs'        => $pdfs,
            'currentPage' => $pdfs->currentPage(),
            'pageNumber'  => $page,
            'perPage'     => $perPage,
            'dateRecord'  => $pdfs->first()->date ?? null,
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
        'date_id'      => $pdf->date_id,                    // include date_id
        'date'         => $pdf->date->date_value ?? null,  // custom date
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
            return $this->error('', 'No PDFs found for this date', 200);
        }

        // Get PDFs for this date
        $pdfs = Pdf::where('date_id', $dateRecord->id)
            ->get(['title', 'short_desc', 'file_path'])
            ->map(function ($pdf) {
                return [
                    'title' => $pdf->title,
                    'short_desc' => $pdf->short_desc,
                    'file_url' => url($pdf->file_path)
                ];
            });


        return $this->success($pdfs, 'Pdfs found for today', 201);
    }


    // Download PDF
    public function downloadPdf($id)
    {
        $pdf = Pdf::find($id);

        if (!$pdf || !file_exists(public_path($pdf->file_path))) {
            return $this->error('', 'Pdf not found', 404);
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

            return $this->error('', 'Unauthenticated.', 401);
        }

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
        ];

        return $this->success($data, 'user information', 201);
    }
}
