<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordem_servico_anexos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ordem_servico_id')
                ->constrained('ordem_servicos')
                ->cascadeOnDelete();

            $table->string('path');               // caminho no storage
            $table->string('nome_original');      // nome do arquivo enviado
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable(); // em bytes

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordem_servico_anexos');
    }
};
