<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::latest();
        
        // Filter by date range
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00', 
                $request->to_date . ' 23:59:59'
            ]);
        }
        
        $orders = $query->paginate(10);
        
        return view('pos_views.orders.list', compact('orders'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variation_id' => 'nullable|exists:variations,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Generate unique order number
        $orderNumber = 'ORD-' . time() . '-' . rand(100, 999);
        
        $order = Order::create([
            'order_number' => $orderNumber,
            'items' => $request->items,
            'subtotal' => $request->subtotal,
            'tax' => $request->tax ?? 0,
            'discount' => $request->discount ?? 0,
            'total' => $request->total,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Order placed successfully',
            'order' => $order
        ]);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('pos_views.orders.show', compact('order'));
    }
}