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
        Schema::create('acessos_liberados', function (Blueprint $table) {
            $table->id();
            $table->string('nome');                   // Nome da pessoa com acesso liberado
            $table->string('matricula')->nullable();  // Matrícula, se aplicável
            $table->string('placa')->nullable();      // Placa do veículo
            $table->date('validade')->nullable();     // Validade do acesso
            $table->boolean('status')->default(true); // Status do acesso (ativo ou não)
            $table->timestamps();                     // created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acessos_liberados');
    }
};
