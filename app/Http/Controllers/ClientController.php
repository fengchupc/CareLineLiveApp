<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller {
    /**
     * Display a listing of the resource.
     * REST - GET
     */
    public function index(Request $request) {
        $perPage = $request->input('per_page', 10); // 10 records per page
        $clients = \App\Models\Client::paginate($perPage);

        return response()->json([
            'data' => $clients->items(),
            'pagination' => [
                'current_page' => $clients->currentPage(),
                'last_page' => $clients->lastPage(),
                'per_page' => $clients->perPage(),
                'total' => $clients->total(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * REST - POST
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'care_needs' => 'nullable|string'
        ]);

        $client = Client::create($validated);
        return response()->json($client, 201);
    }

    /**
     * Display the specified resource.
     * REST - GET
     */
    public function show(Client $client) {
        return response()->json($client);
    }

    /**
     * Update the specified resource in storage.
     * REST - PUT
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('clients')->ignore($client->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'care_needs' => 'nullable|string'
        ]);

        $client->update($validated);
        return response()->json($client);
    }

    /**
     * Remove the specified resource from storage.
     * REST - DELETE
     */
    public function destroy(Client $client) {
        $client->delete();
        return response()->json(null, 204);
    }
}
