<?php

namespace App\Http\Controllers\Api;

use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $time_log_query = TimeLog::query();
        $time_log_query->whereHas('project.client.user', function ($query) {
            $query->where('id', auth()->user()->id);
        });
        if ($request->project_id) {
            $time_log_query->where('project_id', $request->project_id);
        }
        if ($request->client_id) {
            $time_log_query->whereHas('project', function ($q) {
                $q->where('client_id', request('client_id'));
            });
        }
        if ($request->from && $request->to) {
            $time_log_query->whereDate('created_at', '>=', $request->from)
                ->whereDate('created_at', '<=', $request->to);
        }
        $time_logs = $time_log_query->with(['project', 'project.client'])->sum('hours');

        $data['total_hours'] = $time_logs;

        return $this->sendSuccessResponse($data, 'Time Logs retrieved successfully');
    }

    public function per_project_wise_report(Request $request)
    {
        $time_log_query = TimeLog::query();
        $time_log_query->whereHas('project.client.user', function ($query) {
            $query->where('id', auth()->user()->id);
        });
        if ($request->project_id) {
            $time_log_query->where('project_id', $request->project_id);
        }

        $time_logs = $time_log_query->with('project')->sum('hours');
        $data['total_hours'] = $time_logs;

        return $this->sendSuccessResponse($data, 'Time Logs retrieved successfully');
    }

    public function per_day_wise_report(Request $request)
    {
        $time_log_query = TimeLog::query();
        $time_log_query->whereHas('project.client.user', function ($query) {
            $query->where('id', auth()->user()->id);
        });
        if ($request->created_at) {
            $time_log_query->whereDate('created_at', $request->created_at);
        }

        $time_logs = $time_log_query->with('project')->sum('hours');
        $data['total_hours'] = $time_logs;

        return $this->sendSuccessResponse($data, 'Time Logs retrieved successfully');
    }

    public function client_wise_report(Request $request)
    {
        $time_log_query = TimeLog::query();
        $time_log_query->whereHas('project.client.user', function ($query) {
            $query->where('id', auth()->user()->id);
        });
        if ($request->client_id) {
            $time_log_query->whereHas('project', function ($q) {
                $q->where('client_id', request('client_id'));
            });
        }

        $time_logs = $time_log_query->with(['project', 'project.client'])->sum('hours');
        $data['total_hours'] = $time_logs;

        return $this->sendSuccessResponse($data, 'Time Logs retrieved successfully');
    }
}
