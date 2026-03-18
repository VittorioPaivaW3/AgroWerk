<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipamentos', function (Blueprint $table) {
            $table->foreignId('tipo_equipamento_id')
                ->nullable()
                ->after('setor_id')
                ->constrained('tipos_equipamento')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('equipamentos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tipo_equipamento_id');
        });
    }
};

