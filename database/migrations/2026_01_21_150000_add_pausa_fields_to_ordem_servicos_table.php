<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordem_servicos', function (Blueprint $table) {
            $table->timestamp('pausada_em')->nullable()->after('fim_execucao_em');
            $table->unsignedInteger('total_minutos_pausa')->default(0)->after('pausada_em');
            $table->text('observacao_pausa')->nullable()->after('total_minutos_pausa');
        });
    }

    public function down(): void
    {
        Schema::table('ordem_servicos', function (Blueprint $table) {
            $table->dropColumn(['pausada_em', 'total_minutos_pausa', 'observacao_pausa']);
        });
    }
};
