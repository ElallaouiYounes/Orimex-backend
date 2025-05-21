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
            'id' => 'required|string|unique:inventory,id',
            'product_id' => 'required|exists:products,id',
            'current_stock' => 'required|integer|min:0',
            'available_stock' => 'required|integer|min:0',
            'allocated_stock' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'max_stock_level' => 'required|integer|gt:min_stock_level',
            'location' => 'required|string',
            'warehouse' => 'required|string',
        ]);

        // Calculate status automatically
        $validated['status'] = $this->calculateStockStatus(
            $validated['current_stock'],
            $validated['min_stock_level']
        );

        $validated['available_stock'] = $validated['current_stock'];
        $validated['allocated_stock'] = 0;

        $inventory = Inventory::create($validated);

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
            'current_stock' => 'sometimes|integer|min:0',
            'min_stock_level' => 'sometimes|integer|min:0',
            'max_stock_level' => 'sometimes|integer|gt:min_stock_level',
            'location' => 'sometimes|string',
            'warehouse' => 'sometimes|string',
        ]);

        // Recalculate status if stock levels changed
        if (isset($validated['current_stock']) || isset($validated['min_stock_level'])) {
            $currentStock = $validated['current_stock'] ?? $inventory->current_stock;
            $minStock = $validated['min_stock_level'] ?? $inventory->min_stock_level;
            $validated['status'] = $this->calculateStockStatus($currentStock, $minStock);
        }

        $inventory->update($validated);

        return response()->json($inventory);
    }

    public function destroy(string $id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();

        return response()->json(['message' => 'Inventory item deleted']);
    }

    protected function calculateStockStatus(int $currentStock, int $minStockLevel): string
    {
        if ($currentStock <= 0) {
            return 'out-of-stock';
        } elseif ($currentStock <= $minStockLevel) {
            return 'low-stock';
        } else {
            return 'in-stock';
        }
    }
}
