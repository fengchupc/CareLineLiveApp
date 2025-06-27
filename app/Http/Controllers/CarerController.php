<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Carer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CarerController extends Controller {
    /**
     * Display a listing of the resource.
     * REST - GET
     */
    public function index(Request $request) {
        $perPage = $request->input('per_page', 10);
        $carers = \App\Models\Carer::paginate($perPage);
        return response()->json([
            'data' => $carers->items(),
            'pagination' => [
                'current_page' => $carers->currentPage(),
                'last_page' => $carers->lastPage(),
                'per_page' => $carers->perPage(),
                'total' => $carers->total(),
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
            'email' => 'required|email|unique:carers',
            'phone' => 'nullable|string|max:20',
            'qualifications' => 'nullable|string'
        ]);

        $carer = Carer::create($validated);
        return response()->json($carer, 201);
    }

    /**
     * Display the specified resource.
     * REST - GET
     */
    public function show(Carer $carer) {
        return response()->json($carer);
    }

    /**
     * Update the specified resource in storage.
     * REST - PUT
     */
    public function update(Request $request, Carer $carer) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('carers')->ignore($carer->id)],
            'phone' => 'nullable|string|max:20',
            'qualifications' => 'nullable|string'
        ]);

        $carer->update($validated);
        return response()->json($carer);
    }

    /**
     * Remove the specified resource from storage.
     * REST - DELETE
     */
    public function destroy(Carer $carer) {
        $carer->delete();
        return response()->json(null, 204);
    }
}
