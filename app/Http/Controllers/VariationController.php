<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VariationController extends Controller
{
    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        $validator = Validator::make($request->all(), [
            'attributes' => 'required|array',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $variation = new Variation([
            'attributes' => $request->attributes,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'stock' => $request->stock ?? 0
        ]);

        $product->variations()->save($variation);

        return response()->json(['message' => 'Variation added successfully', 'variation' => $variation]);
    }

    public function update(Request $request, $id)
    {
        $variation = Variation::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'attributes' => 'required|array',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $variation->update([
            'attributes' => $request->attributes,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'stock' => $request->stock ?? 0
        ]);

        return response()->json(['message' => 'Variation updated successfully', 'variation' => $variation]);
    }

    public function destroy($id)
    {
        $variation = Variation::findOrFail($id);
        $variation->delete();
        
        return response()->json(['message' => 'Variation deleted successfully']);
    }
}