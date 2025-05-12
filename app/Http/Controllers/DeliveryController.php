<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Delivery::with(['order', 'employee', 'vehicle'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request = validate([
            'order_id' => 'required|exists:orders,id',
            'employee_id' => 'required|exists:team,id',
            'vehicle_id' => 'required|exists:logistics,id',
            'start_date' => 'required|date',
            'est_arrival' => 'required|date|after_or_equal:start_date',
            'route_from' => 'required|string',
            'route_current' => 'required|string',
            'route_to' => 'required|string',
            'status' => 'nullable|in:pending,in-transit,delivered,failed',
            'route_to' => 'required|string',
        ]);

        $delivery = Delivery::create([
            'id' => 'DEL-' .strtoupper(Str::random(6)),
            'order_id' => $request->order_id,
            'employee_id' => $request->employee_id,
            'vehicle_id' => $request->vehicle_id,
            'start_date' => $request->start_date,
            'est_arrival' => $request->est_arrival,
            'route_from' => $request->route_from,
            'route_current' => $request->route_current,
            'route_to' => $request->route_to,
            'status' => $request->status ?? 'pending',
            'contact' => $request->contact,
        ]);

        return response()->json($delivery, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $delivery = Delivery::with(['order', 'employee', 'vehicle'])->findOrFail($id);
        return response()->json($delivery);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $delivery = Delivery::findOrFail($id);

        $request->validate([
            'employee_id' => 'sometimes|exists:team,id',
            'vehicle_id' => 'sometimes|exists:logistics,id',
            'start_date' => 'sometimes|date',
            'est_arrival' => 'sometimes|date|after_or_equal:start_date',
            'route_from' => 'sometimes|string',
            'route_current' => 'sometimes|string',
            'route_to' => 'sometimes|string',
            'status' => 'in:pending,in-transit,delivered,failed',
            'contact' => 'sometimes|string',
        ]);

        $delivery->update($request->all());

        return response()->json($delivery);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delivery = Delivery::findOrFail($id);
        $delivery->delete();

        return response()->json(['message' => 'Delivery deleted successfully']);
    }
}
