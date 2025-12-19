<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipamento_arquivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipamento_id')
                ->constrained('equipamentos')
                ->onDelete('cascade');

            $table->string('path');          // caminho no storage
            $table->string('nome_original'); // nome do arquivo enviado
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable(); // em bytes

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipamento_arquivos');
    }
};
