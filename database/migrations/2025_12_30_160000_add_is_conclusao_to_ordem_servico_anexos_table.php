<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordem_servico_anexos', function (Blueprint $table) {
            $table->boolean('is_conclusao')
                ->default(false)
                ->after('size');
        });
    }

    public function down(): void
    {
        Schema::table('ordem_servico_anexos', function (Blueprint $table) {
            $table->dropColumn('is_conclusao');
        });
    }
};
