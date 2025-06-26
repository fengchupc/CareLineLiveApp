<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\Carer;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ShiftController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return response()->json(Shift::with(['carer', 'client'])->get());
    }

    private function hasOverlappingShift($carerId, $startTime, $endTime, $excludeShiftId = null) {
    
        $startTime = date('Y-m-d H:i:s', strtotime($startTime));
        $endTime = date('Y-m-d H:i:s', strtotime($endTime));

        $query = Shift::where('carer_id', $carerId)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
            });

        if ($excludeShiftId) {
            $query->where('id', '!=', $excludeShiftId);
        }

        \Log::info('Checking overlap SQL', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        return $query->exists();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'carer_id' => 'required|exists:carers,id',
            'client_id' => 'required|exists:clients,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'notes' => 'nullable|string'
        ]);

        if ($this->hasOverlappingShift($validated['carer_id'], $validated['start_time'], $validated['end_time'])) {
            throw ValidationException::withMessages([
                'start_time' => ['The carer already has a shift during this time period.']
            ]);
        }

        $newShift = new Shift([
            'carer_id' => $request->carer_id,
            'client_id' => $request->client_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'notes' => $request->notes
        ]);
        $newShift->save();
        return response()->json($newShift->load(['carer', 'client']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Shift $shift) {
        return response()->json($shift->load(['carer', 'client']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shift $shift) {
        $validated = $request->validate([
            'carer_id' => 'required|exists:carers,id',
            'client_id' => 'required|exists:clients,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'notes' => 'nullable|string'
        ]);

        if ($this->hasOverlappingShift($validated['carer_id'], $validated['start_time'], $validated['end_time'], $shift->id)) {
            throw ValidationException::withMessages([
                'start_time' => ['The carer already has a shift during this time period.']
            ]);
        }

        $shift->update($validated);
        return response()->json($shift->load(['carer', 'client']));
    }

    /**
     * Remove the specified resource from storage. 
     */
    public function destroy(Shift $shift) {
        $shift->delete();
        return response()->json(null, 204);
    }
}
