<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('manutencao_alertas', function (Blueprint $table) {
            $table->timestamp('last_sent_at')->nullable()->after('horimetro_alvo');
        });
    }

    public function down(): void
    {
        Schema::table('manutencao_alertas', function (Blueprint $table) {
            $table->dropColumn('last_sent_at');
        });
    }
};
