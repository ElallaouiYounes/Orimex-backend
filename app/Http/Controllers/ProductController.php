<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('inventory')->get();

        $productsWithStatus = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'barcode' => $product->barcode,
                'name' => $product->name,
                'category' => $product->category,
                'thickness' => $product->thickness,
                'color' => $product->color,
                'dimensions' => $product->dimensions,
                'price' => $product->price,
                'stock_status' => $product->inventory->status ?? 'unknown',
                'stock_levels' => $product->inventory->stock_levels ?? 'unknown',
            ];
        });

        return response()->json($productsWithStatus);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string|unique:products,barcode',
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'thickness' => 'required|string',
            'color' => 'required|string',
            'dimensions' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $lastProduct = Product::latest('created_at')->first();
        $lastId = $lastProduct ? (int)substr($lastProduct->id, 5) : 0;
        $newId = 'PROD-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

        $product = Product::create(array_merge($validated, ['id' => $newId]));

        return response()->json($product, 201);
    }

    public function show(string $id)
    {
        $product = Product::with('inventory')->findOrFail($id);

        return response()->json([
            'id' => $product->id,
            'barcode' => $product->barcode,
            'name' => $product->name,
            'category' => $product->category,
            'thickness' => $product->thickness,
            'color' => $product->color,
            'dimensions' => $product->dimensions,
            'price' => $product->price,
            'stock_status' => $product->inventory->status ?? 'unknown',
        ]);
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'barcode' => 'sometimes|string|unique:products,barcode,' . $product->id,
            'name' => 'sometimes|string|max:255',
            'category' => 'sometimes|string',
            'thickness' => 'sometimes|string',
            'color' => 'sometimes|string',
            'dimensions' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
        ]);

        $product->update($validated);

        return response()->json($product);
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}
