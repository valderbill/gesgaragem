<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('mensagem_destinatarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mensagem_id')->constrained('mensagens')->onDelete('cascade');
            $table->foreignId('destinatario_id')->constrained('usuarios')->onDelete('cascade');
            $table->boolean('lida')->default(false);
            $table->timestamp('data_leitura')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mensagem_destinatarios');
    }
};
