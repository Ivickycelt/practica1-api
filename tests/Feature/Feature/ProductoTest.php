<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\User;
use Tests\TestCase;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    private $categoria;
    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Creamos una categoría global para que los productos tengan su relación requerida
        $this->categoria = Categoria::factory()->create();

        // Creamos un usuario Administrador por si tu lógica de negocio exige roles para borrar
        $this->admin = User::factory()->create([
            'role' => 'admin' // Ajusta esto según cómo gestiones tus roles (ej. Is_admin => true)
        ]);
    }

    public function test_puede_listar_productos(): void
    {
        // Creamos productos de prueba asociados a la categoría
        Producto::create([
            'nombre' => 'Producto de Prueba 1',
            'descripcion' => 'Descripción 1',
            'precio' => 10.00,
            'stock' => 5,
            'categoria_id' => $this->categoria->id,
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
                         ->getJson('/api/productos');

        $response->assertStatus(200)
                 ->assertJsonFragment(['nombre' => 'Producto de Prueba 1']);
    }

    public function test_puede_crear_producto(): void
    {
        $payload = [
            'nombre' => 'Nuevo Producto',
            'descripcion' => 'Nueva Descripción',
            'precio' => 25.50,
            'stock' => 20,
            'categoria_id' => $this->categoria->id, // Categoría obligatoria
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
                         ->postJson('/api/productos', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['nombre' => 'Nuevo Producto']);

        $this->assertDatabaseHas('productos', [
            'nombre' => 'Nuevo Producto'
        ]);
    }

    public function test_cliente_no_puede_eliminar(): void
    {
        // Creamos un usuario común (Cliente) que no debería tener permisos de borrado
        $cliente = User::factory()->create([
            'role' => 'client' // Ajusta este valor según la lógica de tus roles en el controlador
        ]);

        $producto = Producto::create([
            'nombre' => 'Producto Intocable',
            'descripcion' => 'No se puede borrar',
            'precio' => 99.99,
            'stock' => 1,
            'categoria_id' => $this->categoria->id,
        ]);

        // REPARADO: Se quitaron las llaves dobles de la URL para evitar el Error 500
        $response = $this->actingAs($cliente, 'sanctum')
                         ->deleteJson("/api/productos/{$producto->id}");

        // El test espera un código 403 Forbidden (Prohibido)
        $response->assertForbidden(); 
    }
}