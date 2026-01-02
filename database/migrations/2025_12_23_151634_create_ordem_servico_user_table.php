<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordem_servico_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ordem_servico_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('papel', 20); // 'tecnico' ou 'gestor'
            $table->timestamps();

            $table->unique(['ordem_servico_id', 'user_id', 'papel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordem_servico_user');
    }
};
