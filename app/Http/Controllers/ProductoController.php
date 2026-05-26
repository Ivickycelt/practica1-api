<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function index()
    {
        return response()->json(Producto::all(), 200);
    }

    public function store(ProductoRequest $request)
    {
        $producto = Producto::create($request->validated());

        return response()->json($producto, 201);
    }

    public function show(Producto $producto)
    {
        return response()->json($producto, 200);
    }

    public function update(ProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());

        return response()->json($producto, 200);
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return response()->json(null, 204);
    }
}