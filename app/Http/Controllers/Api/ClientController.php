<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Client\StoreClientRequest;
use App\Http\Requests\Api\Client\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $client_query = Client::query();
        $client_query->where('user_id', auth()->user()->id);
        $clients = $client_query->with('user')->paginate(10);
        
        return $this->sendSuccessResponse(ClientResource::collection($clients)->response()->getData(), 'Clients retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        Client::create($request->validated());
        return $this->sendSuccessResponse([], 'Client created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function edit(Client $client)
    {
        if($client->user_id != auth()->user()->id) {
            return $this->sendErrorResponse('Unauthorized data access', 403);
        }
        
        return $this->sendSuccessResponse(new ClientResource($client), 'Client retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->update($request->validated());
        return $this->sendSuccessResponse([], 'Client updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
