<?php

namespace Tests\Feature;

use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_productos_list(): void
    {
        Producto::create([
            'nombre' => 'Camiseta',
            'descripcion' => 'Camiseta de algodón',
            'precio' => 19.99,
            'stock' => 10,
        ]);

        $response = $this->getJson('/api/productos');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['nombre' => 'Camiseta']);
    }

    public function test_store_creates_producto_with_valid_data(): void
    {
        $payload = [
            'nombre' => 'Zapatos',
            'descripcion' => 'Zapatos deportivos',
            'precio' => 79.95,
            'stock' => 5,
        ];

        $response = $this->postJson('/api/productos', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['nombre' => 'Zapatos']);

        $this->assertDatabaseHas('productos', ['nombre' => 'Zapatos']);
    }

    public function test_show_returns_single_producto(): void
    {
        $producto = Producto::create([
            'nombre' => 'Gorra',
            'descripcion' => 'Gorra con logo',
            'precio' => 15.00,
            'stock' => 3,
        ]);

        $response = $this->getJson("/api/productos/{$producto->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['nombre' => 'Gorra']);
    }

    public function test_update_modifies_producto(): void
    {
        $producto = Producto::create([
            'nombre' => 'Bolso',
            'descripcion' => 'Bolso de mano',
            'precio' => 45.00,
            'stock' => 2,
        ]);

        $payload = [
            'nombre' => 'Bolso XL',
            'descripcion' => 'Bolso de mano grande',
            'precio' => 50.00,
            'stock' => 4,
        ];

        $response = $this->putJson("/api/productos/{$producto->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment(['nombre' => 'Bolso XL']);

        $this->assertDatabaseHas('productos', ['nombre' => 'Bolso XL', 'stock' => 4]);
    }

    public function test_destroy_deletes_producto(): void
    {
        $producto = Producto::create([
            'nombre' => 'Llavero',
            'descripcion' => 'Llavero metálico',
            'precio' => 5.50,
            'stock' => 15,
        ]);

        $response = $this->deleteJson("/api/productos/{$producto->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('productos', ['id' => $producto->id]);
    }
}
