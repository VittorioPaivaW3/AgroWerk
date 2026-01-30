<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipamentos', function (Blueprint $table) {
            $table->unsignedInteger('vida_util_h')->nullable()->after('status');
            $table->decimal('horimetro', 10, 2)->nullable()->after('vida_util_h');
        });
    }

    public function down(): void
    {
        Schema::table('equipamentos', function (Blueprint $table) {
            $table->dropColumn(['vida_util_h', 'horimetro']);
        });
    }
};
