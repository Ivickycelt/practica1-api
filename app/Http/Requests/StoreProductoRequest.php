<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 🟢 Permitimos el acceso al formulario [cite: 26, 27]
    }

    public function rules(): array
    {
        return [
            'nombre'       => 'required|string|min:3|max:100', // [cite: 31]
            'descripcion'  => 'nullable|string|max:500', // [cite: 32]
            'precio'       => 'required|numeric|min:0.01|max:99999', // [cite: 33]
            'stock'        => 'required|integer|min:0', // [cite: 34]
            'categoria_id' => 'nullable|exists:categorias,id', // [cite: 35]
            'imagen'       => 'nullable|image|mimes:jpg,png,webp|max:2048', // [cite: 36]
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'   => 'El nombre del producto es obligatorio.', // [cite: 41]
            'nombre.min'        => 'El nombre debe tener al menos :min caracteres.', // [cite: 42]
            'precio.required'   => 'Debes indicar un precio.', // [cite: 43]
            'precio.min'        => 'El precio debe ser mayor a cero.', // [cite: 44]
            'stock.integer'     => 'El stock debe ser un número entero.', // [cite: 45]
            'categoria_id.exists' => 'La categoría seleccionada no existe.', // [cite: 46]
            'imagen.mimes'      => 'La imagen debe ser JPG, PNG o WEBP.', // [cite: 47]
            'imagen.max'        => 'La imagen no puede superar 2 MB.', // 
        ];
    }
}