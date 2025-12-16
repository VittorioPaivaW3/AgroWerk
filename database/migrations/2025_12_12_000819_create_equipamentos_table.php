<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipamentos', function (Blueprint $table) {
            $table->id(); // esse é seu "código" principal

            $table->string('codigo')->nullable(); 
            // opcional: se você quiser um código próprio do cliente, diferente do id

            $table->string('nome');

            // FK para setores
            $table->foreignId('setor_id')
                  ->constrained('setores')
                  ->cascadeOnDelete();

            // Campos dinâmicos (JSON)
            $table->json('campos_extras')->nullable();
            /*
                Exemplo de conteúdo:
                {
                    "n_serie": "123",
                    "potencia_cv": "75",
                    "fabricante": "Valtra"
                }
            */

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipamentos');
    }
};

