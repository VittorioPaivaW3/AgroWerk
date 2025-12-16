<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipamentos', function (Blueprint $table) {
            $table->text('observacoes')->nullable()->after('campos_extras');
            $table->date('manutencao_preventiva')->nullable()->after('observacoes');
        });
    }

    public function down(): void
    {
        Schema::table('equipamentos', function (Blueprint $table) {
            $table->dropColumn(['observacoes', 'manutencao_preventiva']);
        });
    }
};
