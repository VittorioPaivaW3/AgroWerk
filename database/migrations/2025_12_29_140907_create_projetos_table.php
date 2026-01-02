<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projetos', function (Blueprint $table) {
            $table->id();

            // relação com setor
            $table->foreignId('setores_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('titulo');                // título do projeto
            $table->text('descricao')->nullable();   // descrição da demanda

            $table->date('prazo')->nullable();       // prazo final

            // orçamento (pode ter previsto e real, se quiser)
            $table->decimal('orcamento_previsto', 12, 2)->nullable();
            $table->decimal('orcamento_real', 12, 2)->nullable();

            $table->enum('status', [
                'aberto',
                'em_andamento',
                'concluido',
                'cancelado',
            ])->default('aberto');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projetos');
    }
};
