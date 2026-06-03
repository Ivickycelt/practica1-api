<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        return [
            // Las llaves DEBEN ser los nombres reales de las columnas en tu base de datos:
            'nombre'      => $this->faker->words(3, true), 
            'precio'      => $this->faker->randomFloat(2, 10, 1500),
            'stock'       => $this->faker->numberBetween(5, 50),
            'descripcion' => $this->faker->sentence(10),
            'imagen'      => null,
        ];
    }
}