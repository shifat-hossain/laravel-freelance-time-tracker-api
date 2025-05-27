<?php

namespace App\Http\Controllers\Api\TimeLog;

use App\Http\Controllers\Controller;
use App\Models\TimeLog;
use Illuminate\Http\Request;

class PdfTimeLogController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $time_log_query = TimeLog::query();
        $time_log_query->whereHas('project.client.user', function ($query) {
            // $query->where('id', auth()->user()->id);
            $query->where('id', 1);
        });
        if ($request->interval_type) {
            //interval_type can be 'day', 'week', 'month' etc.
            $interval = $request->interval_type ?? 'day';
            $date = now()->sub($interval, 1);
            $time_log_query->where('created_at', '>=', $date);
        }
        $time_logs = $time_log_query->with('project')->get();

        $mpdf = new \Mpdf\Mpdf();
        $html = view('pdf.timelog', [
            'time_logs' => $time_logs,
        ])->render();
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}
