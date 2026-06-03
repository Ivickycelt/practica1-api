<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'slug', 'descripcion'];

    // Esta es la relación inversa a la que definimos en Producto.php
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class); //
    }
}