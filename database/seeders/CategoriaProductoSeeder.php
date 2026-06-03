<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriaProductoSeeder extends Seeder
{
    public function run(): void
    {
        // Definimos el catálogo estructurado en español
        $datos = [
            'Electrónica' => [
                ['nombre' => 'Laptop HP ProBook', 'precio' => 12500, 'desc' => 'Laptop potente para oficina y desarrollo con 16GB RAM.'],
                ['nombre' => 'Smartphone Samsung Galaxy', 'precio' => 8999, 'desc' => 'Pantalla AMOLED y cámara de alta resolución.'],
                ['nombre' => 'Audífonos Sony Noise Cancelling', 'precio' => 3499, 'desc' => 'Cancelación de ruido activa e ideal para viajes.'],
                ['nombre' => 'Monitor Gamer Asus 24"', 'precio' => 4200, 'desc' => 'Frecuencia de actualización de 144Hz y 1ms de respuesta.'],
                ['nombre' => 'Teclado Mecánico RGB', 'precio' => 1200, 'desc' => 'Switches red, silencioso y retroiluminación personalizada.']
            ],
            'Ropa' => [
                ['nombre' => 'Chaqueta de Mezclilla', 'precio' => 850, 'desc' => 'Chaqueta clásica de mezclilla azul, corte regular.'],
                ['nombre' => 'Tenis Deportivos Running', 'precio' => 1599, 'desc' => 'Suela amortiguada ideal para correr y entrenar.'],
                ['nombre' => 'Sudadera con Capucha Negra', 'precio' => 600, 'desc' => 'Algodón cómodo con bolsa frontal tipo canguro.'],
                ['nombre' => 'Pantalón Slim Fit Kaki', 'precio' => 550, 'desc' => 'Pantalón casual cómodo para uso diario.'],
                ['nombre' => 'Camisa Formal Blanca', 'precio' => 480, 'desc' => 'Corte ajustado ideal para juntas y eventos formales.']
            ],
            'Hogar' => [
                ['nombre' => 'Cafetera de Goteo Programable', 'precio' => 950, 'desc' => 'Capacidad de 12 tazas con filtro permanente lavable.'],
                ['nombre' => 'Juego de Sarténes Antiadherentes', 'precio' => 1350, 'desc' => 'Kit de 3 sartenes de aluminio con revestimiento de cerámica.'],
                ['nombre' => 'Lámpara de Escritorio LED', 'precio' => 350, 'desc' => 'Brazo flexible y tres niveles de intensidad de luz.'],
                ['nombre' => 'Licuadora de Alta Potencia', 'precio' => 1800, 'desc' => 'Vaso de vidrio con 4 velocidades y cuchillas de acero.'],
                ['nombre' => 'Juego de Sábanas Matrimonial', 'precio' => 450, 'desc' => 'Microfibra ultra suave, color gris Oxford hipoalergénico.']
            ],
            'Deportes' => [
                ['nombre' => 'Balón de Fútbol Profesional', 'precio' => 650, 'desc' => 'Tamaño No. 5, cosido a mano ideal para canchas sintéticas.'],
                ['nombre' => 'Tapete de Yoga Antideslizante', 'precio' => 380, 'desc' => 'Grosor de 6mm con correa de transporte incluida.'],
                ['nombre' => 'Mochila de Senderismo 40L', 'precio' => 1100, 'desc' => 'Impermeable con múltiples compartimentos y soporte lumbar.'],
                ['nombre' => 'Mancuernas Ajustables (Par)', 'precio' => 2400, 'desc' => 'Sistema de discos intercambiables de hasta 10kg cada una.'],
                ['nombre' => 'Botella de Agua Térmica 1L', 'precio' => 299, 'desc' => 'Acero inoxidable que mantiene el frío por 24 horas.']
            ]
        ];

        foreach ($datos as $nombreCategoria => $listaProductos) {
            // Creamos la categoría
            $cat = Categoria::create([
                'nombre'      => $nombreCategoria,
                'slug'        => Str::slug($nombreCategoria),
                'descripcion' => "Sección especializada en artículos de $nombreCategoria."
            ]);

            // Insertamos sus 5 productos reales en español
            foreach ($listaProductos as $p) {
                Producto::create([
                    'categoria_id' => $cat->id,
                    'nombre'       => $p['nombre'],
                    'precio'       => $p['precio'],
                    'stock'        => rand(10, 40),
                    'descripcion'  => $p['desc'],
                    'imagen'       => null
                ]);
            }
        }
    }
}