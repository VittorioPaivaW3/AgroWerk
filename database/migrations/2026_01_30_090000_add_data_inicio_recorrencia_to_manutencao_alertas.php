<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('manutencao_alertas', function (Blueprint $table) {
            $table->date('data_inicio_recorrencia')->nullable()->after('recorrente');
        });
    }

    public function down(): void
    {
        Schema::table('manutencao_alertas', function (Blueprint $table) {
            $table->dropColumn('data_inicio_recorrencia');
        });
    }
};
