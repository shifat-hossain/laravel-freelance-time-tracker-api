<?php

namespace App\Http\Controllers\Api\TimeLog;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\TimeLog\StoreTimeLogRequest;
use App\Http\Requests\Api\TimeLog\UpdateTimeLogRequest;
use App\Http\Resources\TimeLogResource;
use App\Mail\NotificationMailManager;
use App\Models\Project;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TimeLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $time_log_query = TimeLog::query();
        $time_log_query->whereHas('project.client.user', function ($query) {
            $query->where('id', auth()->user()->id);
        });
        if ($request->interval_type) {
            //interval_type can be 'day', 'week', 'month' etc.
            $interval = $request->interval_type ?? 'day';
            $date = now()->sub($interval, 1);
            $time_log_query->where('created_at', '>=', $date);
        }

        $time_logs = $time_log_query->with('project')->paginate(10);

        return $this->sendSuccessResponse(TimeLogResource::collection($time_logs)->response()->getData(), 'Time Logs retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTimeLogRequest $request)
    {
        $project = Project::find($request->project_id);
        if ($project->client->user_id != auth()->user()->id) {
            return $this->sendErrorResponse('Unauthorized data access', 403);
        }

        $time_log = TimeLog::create($request->validated());

        return $this->sendSuccessResponse([], 'Time log created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function edit(TimeLog $time_log)
    {
        if ($time_log->project->client->user_id != auth()->user()->id) {
            return $this->sendErrorResponse('Unauthorized data access', 403);
        }
        return $this->sendSuccessResponse(new TimeLogResource($time_log), 'Time log retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTimeLogRequest $request, TimeLog $time_log)
    {
        $project = Project::find($request->project_id);
        if (
            $time_log->project->client->user_id != auth()->user()->id ||
            $project->client->user_id != auth()->user()->id
        ) {
            return $this->sendErrorResponse('Unauthorized data access', 403);
        }
        
        $time_log->update($request->validated());
        if ($time_log->hours > 8) {
            try {
                Mail::to(auth()->user()->email)->send(new NotificationMailManager($time_log));
            } catch (\Exception $e) {
                return $this->sendErrorResponse([], 'Failed to send email notification: ' . $e->getMessage());
            }
        }
        return $this->sendSuccessResponse([], 'Time log updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimeLog $time_log)
    {
        if ($time_log->project->client->user_id != auth()->user()->id) {
            return $this->sendErrorResponse('Unauthorized data access', 403);
        }
        $time_log->delete();
        return $this->sendSuccessResponse([], 'Time log deleted successfully');
    }
}
