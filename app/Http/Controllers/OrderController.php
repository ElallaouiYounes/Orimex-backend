<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        return response()->json(Order::with('customer', 'product')->get());
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'expected_delivery' => 'required|date',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $totalPrice = $product->price * $validated['quantity'];

        // Generate custom order ID
        $lastOrder = Order::latest('created_at')->first();
        $lastIdNumber = $lastOrder ? (int)substr($lastOrder->id, 4) : 0;
        $newId = 'ORD-' . str_pad($lastIdNumber + 1, 5, '0', STR_PAD_LEFT);

        $order = Order::create([
            'id' => $newId,
            'customer_id' => $validated['customer_id'],
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'expected_delivery' => $validated['expected_delivery'],
            'status' => 'pending',
            'total_price' => $totalPrice,
        ]);

        return response()->json($order->load('customer', 'product'), 201);
    }

    /**
     * Display the specified order.
     */
    public function show(string $id)
    {
        $order = Order::with('customer', 'product')->findOrFail($id);
        return response()->json($order);
    }

    /**
     * Update the specified order.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'quantity' => 'sometimes|integer|min:1',
            'status' => 'sometimes|in:pending,processing,shipped,delivered,cancelled',
            'expected_delivery' => 'sometimes|date',
        ]);

        if (isset($validated['quantity'])) {
            $product = Product::findOrFail($order->product_id);
            $order->total_price = $product->price * $validated['quantity'];
        }

        $order->update($validated);

        return response()->json($order->load('customer', 'product'));
    }

    /**
     * Remove the specified order.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
