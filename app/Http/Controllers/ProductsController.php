<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Products::all();

        return $products;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        if ($this->skuExists($request->sku)) {
            return response()->json(['error' => 'El SKU ya existe.'], 422);
        }

        $request->validate([
            'sku' => 'required|unique:products,sku',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => ['required', Rule::in(['Activo', 'Inactivo'])],
        ]);

        $producto = Products::create($request->all());
        return response()->json($producto, 201);
    }

    private function skuExists($sku)
    {
        return Products::where('sku', $sku)->exists();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $producto = Products::findOrFail($id);
        return response()->json($producto);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $producto = Products::findOrFail($id);

        $request->validate([
            'sku' => ['required', Rule::unique('products')->ignore($producto->id)],
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => ['required', Rule::in(['Activo', 'Inactivo'])],
        ]);

        $producto->update($request->all());
        return response()->json($producto);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $producto = Products::findOrFail($id);
        $producto->delete();
        return response()->json(['message' => 'Producto eliminado']);
    }
}
