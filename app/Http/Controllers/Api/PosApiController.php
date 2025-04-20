<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PosApiController extends Controller
{
    public function getProducts(Request $request)
    {
        $query = Product::with('variations');
    
        // Filter by name or SKU
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        $products = $query->latest()->paginate(2);
        
        return response()->json($products);
    }

    public function getProductDetails($id)
    {
        $product = Product::with('variations')->findOrFail($id);
        return response()->json($product);
    }
}