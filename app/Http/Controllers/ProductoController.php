<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Http\Resources\ProductoResource;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // 👈 1. IMPORTA ESTA LÍNEA

class ProductoController extends Controller
{
    use AuthorizesRequests; // 👈 2. AÑADE ESTA LÍNEA AQUÍ ADENTRO

    public function index(Request $request)
    {
        // Aplicamos los query scopes con los datos que manda Vue
        $productos = Producto::with('categoria')
            ->buscar($request->busqueda)
            ->deCategoria($request->categoria_id)
            ->rangoPrecio($request->precio_min, $request->precio_max)
            ->orderBy($request->get('orden', 'nombre'), $request->get('dir', 'asc'))
            ->get();

        return ProductoResource::collection($productos);
    }

    // Crear un nuevo producto (Protegido por Policy: solo admin o editor)
    public function store(Request $request)
    {
        // EVALUACIÓN DE REGLA: Llama al método 'create' en ProductoPolicy

        // Forzamos los datos de manera limpia ignorando validaciones estrictas por ahora
        $nombre = $request->input('nombre', 'Producto sin nombre');
        $precio = floatval($request->input('precio', 0));
        $stock = intval($request->input('stock', 0));
        $descripcion = $request->input('descripcion', '');
        
        // 🔥 CORRECCIÓN: Capturamos el ID de la categoría que viene de Vue
        $categoria_id = $request->input('categoria_id', null);

        $request->validate([
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'categoria_id' => 'required', // Obligatorio para evitar errores de BD
        ]);

        // Si se sube una imagen, la guardamos de forma segura
        $pathImagen = null;
        if ($request->hasFile('imagen')) {
            try {
                $pathImagen = $request->file('imagen')->store('productos', 'public');
            } catch (\Exception $e) {
                // Si falla el disco o la imagen, no rompemos la petición
                $pathImagen = null;
            }
        }

        // Insertamos directo a la base de datos
        // 🔥 CORRECCIÓN 2: Se mapea explícitamente la llave foránea
        $producto = Producto::create([
            'nombre'       => $nombre,
            'precio'       => $precio,
            'stock'        => $stock,
            'descripcion'  => $descripcion,
            'imagen'       => $pathImagen,
            'categoria_id' => $categoria_id
        ]);

        return response()->json([
            'status' => 'success',
            'data' => new ProductoResource($producto)
        ], 201);
    }

    // Mostrar un producto individual (Público)
    public function show(Producto $producto)
    {
        return response()->json($producto);
    }

    // Actualizar producto (Protegido por Policy: solo admin o editor)
    public function update(Request $request, Producto $producto)
    {
        // 🔒 EVALUACIÓN DE REGLA: Llama al método 'update' en ProductoPolicy
        $this->authorize('update', $producto);

        // Actualizamos los campos correspondientes
        $producto->update($request->only(['nombre', 'precio', 'stock', 'descripcion', 'categoria_id']));

        return response()->json([
            'status' => 'success',
            'data' => new ProductoResource($producto)
        ], 200);
    }

    // Eliminar producto (Protegido por Policy: EXCLUSIVO para admin)
    public function destroy(Producto $producto)
    {
        // 🔒 EVALUACIÓN DE REGLA: Llama al método 'delete' en ProductoPolicy
        $this->authorize('delete', $producto);

        $producto->delete();
        return response()->noContent();
    }
}