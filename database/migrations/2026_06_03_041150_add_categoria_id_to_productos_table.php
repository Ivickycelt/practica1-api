<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('productos', function (Blueprint $table) {
        // Añade la llave foránea conectada a la tabla categorías
        $table->foreignId('categoria_id')
              ->nullable()
              ->after('id')
              ->constrained('categorias')
              ->nullOnDelete(); // Si se borra la categoría, el producto no se elimina (queda en NULL)
    });
}

public function down(): void
{
    Schema::table('productos', function (Blueprint $table) {
        $table->dropForeign(['categoria_id']);
        $table->dropColumn('categoria_id');
    });
}
};
