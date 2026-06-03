<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Producto extends Model
{
    use HasFactory;

    // Habilitación de asignación masiva segura
    protected $fillable = [
        'nombre', 
        'precio', 
        'stock', 
        'descripcion', 
        'imagen', 
        'categoria_id' // 🔥 Permitido explícitamente para el Producto::create
    ];

    // Relación: Un producto pertenece a una Categoria
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    // Filtro para buscar por texto en nombre o descripción
    public function scopeBuscar($query, $termino)
    {
        return $query->when($termino, function ($q) use ($termino) {
            $q->where('nombre', 'LIKE', "%{$termino}%")
              ->orWhere('descripcion', 'LIKE', "%{$termino}%");
        });
    }

    // Filtro para filtrar por el ID de la categoría
    public function scopeDeCategoria($query, $categoriaId)
    {
        return $query->when($categoriaId, function ($q) use ($categoriaId) {
            $q->where('categoria_id', $categoriaId);
        });
    }

    // Filtro para filtrar por rango de precios mínimo y máximo
    public function scopeRangoPrecio($query, $min, $max)
    {
        return $query
            ->when($min, fn($q) => $q->where('precio', '>=', $min))
            ->when($max, fn($q) => $q->where('precio', '<=', $max)); 
    }
}