<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase; // Resetea la BD virtual en cada test [cite: 168]

    public function test_usuario_puede_registrarse(): void
    {
        $response = $this->postJson('/api/register', [
            'name'                  => 'Juan López', // [cite: 171]
            'email'                 => 'juan@test.com', // [cite: 172]
            'password'              => 'password123', // [cite: 173]
            'password_confirmation' => 'password123', // [cite: 174]
        ]);

        $response->assertStatus(201) // [cite: 176]
                 ->assertJsonStructure(['token', 'user']); // [cite: 177]
                 
        $this->assertDatabaseHas('users', ['email' => 'juan@test.com']); // [cite: 178]
    }

    public function test_login_con_credenciales_incorrectas(): void
    {
        $response = $this->postJson('/api/login', [
            'email'    => 'noexiste@test.com', // [cite: 181]
            'password' => 'wrongpass', // [cite: 182]
        ]);
        
        $response->assertStatus(401); // [cite: 185]
    }
}