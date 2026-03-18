<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipamentos', function (Blueprint $table) {
            $table->boolean('tem_horimetro')
                ->default(false)
                ->after('horimetro');
        });

        DB::table('equipamentos')
            ->whereNotNull('vida_util_h')
            ->orWhereNotNull('horimetro')
            ->update(['tem_horimetro' => true]);
    }

    public function down(): void
    {
        Schema::table('equipamentos', function (Blueprint $table) {
            $table->dropColumn('tem_horimetro');
        });
    }
};
