<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        return response()->json(Inventory::with('product')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'stock_levels' => 'required|integer',
            'location' => 'required|string',
            'warehouse' => 'required|string',
            'status' => 'required|in:in-stock,out-of-stock,low stock',
        ]);

        $lastInventory = Inventory::latest('created_at')->first();
        $lastId = $lastInventory ? (int)substr($lastInventory->id, 5) : 0;
        $newId = 'INV-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

        $inventory = Inventory::create(array_merge($validated, ['id' => $newId]));

        return response()->json($inventory, 201);
    }

    public function show(string $id)
    {
        $inventory = Inventory::with('product')->findOrFail($id);
        return response()->json($inventory);
    }

    public function update(Request $request, string $id)
    {
        $inventory = Inventory::findOrFail($id);

        $validated = $request->validate([
            'stock_levels' => 'sometimes|integer',
            'status' => 'sometimes|in:in-stock,out-of-stock,low stock',
            'location' => 'sometimes|string',
            'warehouse' => 'sometimes|string',
        ]);

        $inventory->update($validated);

        return response()->json($inventory);
    }

    public function destroy(string $id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();

        return response()->json(['message' => 'Inventory item deleted']);
    }
}
