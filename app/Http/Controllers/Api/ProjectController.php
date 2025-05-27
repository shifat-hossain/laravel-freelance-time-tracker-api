<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Project\StoreProjectRequest;
use App\Http\Requests\Api\Project\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $client_ids = auth()->user()->clients->pluck('id')->toArray();
        $project_query = Project::query();
        $project_query->whereIn('client_id', $client_ids);
        $projects = $project_query->with('client')->paginate(10);
        
        return $this->sendSuccessResponse(ProjectResource::collection($projects)->response()->getData(), 'Projects retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $client = Client::find($request->client_id);
        if($client->user_id != auth()->user()->id) {
            return $this->sendErrorResponse('Unauthorized data access', 403);
        }
        
        Project::create($request->validated());
        return $this->sendSuccessResponse([], 'Project created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function edit(Project $project)
    {
        if($project->client->user_id != auth()->user()->id) {
            return $this->sendErrorResponse('Unauthorized data access', 403);
        }
        return $this->sendSuccessResponse(new ProjectResource($project), 'Project retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $client = Client::find($request->client_id);
        
        if($project->client->user_id != auth()->user()->id || $client->user_id != auth()->user()->id) {
            return $this->sendErrorResponse('Unauthorized data access', 403);
        }

        $project->update($request->validated());
        return $this->sendSuccessResponse([], 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if($project->client->user_id != auth()->user()->id) {
            return $this->sendErrorResponse('Unauthorized data access', 403);
        }
        $project->time_logs()->delete();
        $project->delete();
        return $this->sendSuccessResponse([], 'Project deleted successfully');
    }
}
