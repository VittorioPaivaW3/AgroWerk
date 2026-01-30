<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manutencao_alertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipamento_id')->constrained('equipamentos')->cascadeOnDelete();
            $table->text('mensagem')->nullable();
            $table->string('tipo', 20); // data | horimetro
            $table->boolean('recorrente')->default(false);
            $table->unsignedInteger('dias_recorrencia')->nullable();
            $table->date('data_alerta')->nullable();
            $table->decimal('horimetro_alvo', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manutencao_alertas');
    }
};
