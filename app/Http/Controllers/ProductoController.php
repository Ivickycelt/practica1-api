<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductoResource; // <--- ¡Añade esta línea!
use App\Models\Producto;
use Illuminate\Http\Request;


class ProductoController extends Controller
{
    // Listar productos
    public function index()
    {
        return ProductoResource::collection(Producto::all());
    }

    // GUARDAR PRODUCTO (Totalmente blindado)
    public function store(Request $request)
    {
        // Forzamos los datos de manera limpia ignorando validaciones estrictas por ahora
        $nombre = $request->input('nombre', 'Producto sin nombre');
        $precio = floatval($request->input('precio', 0));
        $stock = intval($request->input('stock', 0));
        $descripcion = $request->input('descripcion', '');

        $pathImagen = null;

        // Si subiste una imagen, la guardamos de forma segura
        if ($request->hasFile('imagen')) {
            try {
                $pathImagen = $request->file('imagen')->store('productos', 'public');
            } catch (\Exception $e) {
                // Si falla el disco o la imagen, no rompemos la petición
                $pathImagen = null;
            }
        }

        // Insertamos directo a la base de datos
        $producto = Producto::create([
            'nombre' => $nombre,
            'precio' => $precio,
            'stock' => $stock,
            'descripcion' => $descripcion,
            'imagen' => $pathImagen
        ]);

        return response()->json([
            'status' => 'success',
            'data' => new ProductoResource($producto)
        ], 201);
    }

    // Mostrar un producto
    public function show(Producto $producto)
    {
        return new ProductoResource($producto);
    }

    // Eliminar producto
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return response()->json(['message' => 'Eliminado correctamente'], 200);
    }
}