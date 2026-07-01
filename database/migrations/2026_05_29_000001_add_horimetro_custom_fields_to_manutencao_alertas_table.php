<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('manutencao_alertas', function (Blueprint $table) {
            $table->string('nome')->nullable()->after('equipamento_id');
            $table->decimal('horimetro_intervalo', 10, 2)->nullable()->after('horimetro_alvo');
            $table->decimal('horimetro_base', 10, 2)->nullable()->after('horimetro_intervalo');
            $table->decimal('horimetro_antecedencia', 10, 2)->default(10)->after('horimetro_base');
            $table->timestamp('ultimo_realizado_em')->nullable()->after('last_sent_at');
            $table->decimal('ultimo_realizado_horimetro', 10, 2)->nullable()->after('ultimo_realizado_em');
            $table->boolean('ativo')->default(true)->after('ultimo_realizado_horimetro');
        });
    }

    public function down(): void
    {
        Schema::table('manutencao_alertas', function (Blueprint $table) {
            $table->dropColumn([
                'nome',
                'horimetro_intervalo',
                'horimetro_base',
                'horimetro_antecedencia',
                'ultimo_realizado_em',
                'ultimo_realizado_horimetro',
                'ativo',
            ]);
        });
    }
};
