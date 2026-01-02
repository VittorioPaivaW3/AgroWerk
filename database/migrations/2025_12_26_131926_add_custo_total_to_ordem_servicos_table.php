<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::table('ordem_servicos', function (Blueprint $table) {
        $table->decimal('custo_total', 10, 2)
            ->nullable()
            ->after('prioridade'); // ou em outro lugar que fizer sentido
    });
    }

    public function down(): void
    {
    Schema::table('ordem_servicos', function (Blueprint $table) {
        $table->dropColumn('custo_total');
    });
    }

};
