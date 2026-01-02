<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordem_servicos', function (Blueprint $table) {
            $table->timestamp('inicio_execucao_em')->nullable()->after('status');
            $table->timestamp('fim_execucao_em')->nullable()->after('inicio_execucao_em');
        });
    }

    public function down(): void
    {
        Schema::table('ordem_servicos', function (Blueprint $table) {
            $table->dropColumn(['inicio_execucao_em', 'fim_execucao_em']);
        });
    }
};