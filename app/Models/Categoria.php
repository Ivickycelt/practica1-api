<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany; // 👈 AGREGA ESTA IMPORTACIÓN AQUÍ

class Categoria extends Model
{
    use HasFactory;

    // Asegúrate de agregar el fillable por si tu base de datos exige registrar estos campos
    protected $fillable = ['nombre', 'slug']; 

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }
}