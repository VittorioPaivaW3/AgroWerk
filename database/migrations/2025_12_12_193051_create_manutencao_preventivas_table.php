<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manutencoes_preventivas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('equipamento_id')
                  ->constrained('equipamentos')
                  ->cascadeOnDelete();

            // o que deve ser feito
            $table->text('descricao');

            // data prevista da preventiva (opcional)
            $table->date('data_prevista')->nullable();

            // status simples (pendente / concluida, etc.) — opcional
            $table->string('status', 20)->default('pendente');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manutencoes_preventivas');
    }
};

