<?php

namespace App\Policies;

use App\Models\Producto;
use App\Models\User;

class ProductoPolicy
{
    // Determina si el usuario puede registrar un producto (admin o editor)
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    // Determina si el usuario puede modificar un producto (admin o editor)
    public function update(User $user, Producto $producto): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    // Determina si el usuario puede eliminar un producto (únicamente admin)
    public function delete(User $user, Producto $producto): bool
    {
        return $user->esAdmin();
    }
}