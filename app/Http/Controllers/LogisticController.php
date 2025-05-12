<?php

namespace App\Http\Controllers;

use App\Models\Logistics;
use Illuminate\Http\Request;

class LogisticController extends Controller
{
    public function index()
    {
        return response()->json(Logistics::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'license_plate' => 'required|string',
            'type' => 'required|string',
            'capacity' => 'required|integer',
            'current_location' => 'required|string',
            'status' => 'required|in:available,in-transit,under-maintenance',
            'driver' => 'required|string',
            'last_maintenance' => 'required|date',
            'next_maintenance' => 'required|date',
            'model' => 'required|string',
        ]);

        $logistics = Logistics::create($validated);

        return response()->json($logistics, 201);
    }

    public function show(string $id)
    {
        $logistics = Logistics::findOrFail($id);
        return response()->json($logistics);
    }

    public function update(Request $request, string $id)
    {
        $logistics = Logistics::findOrFail($id);

        $validated = $request->validate([
            'status' => 'sometimes|in:available,in-transit,under-maintenance',
            'driver' => 'sometimes|string',
            'current_location' => 'sometimes|string',
            'last_maintenance' => 'sometimes|date',
            'next_maintenance' => 'sometimes|date',
        ]);

        $logistics->update($validated);

        return response()->json($logistics);
    }

    public function destroy(string $id)
    {
        $logistics = Logistics::findOrFail($id);
        $logistics->delete();

        return response()->json(['message' => 'Logistics item deleted']);
    }
}
