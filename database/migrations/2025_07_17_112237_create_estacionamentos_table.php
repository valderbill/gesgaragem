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
        Schema::create('estacionamentos', function (Blueprint $table) {
            $table->id();
            $table->string('localizacao');
            $table->integer('vagas_particulares')->nullable();
            $table->integer('vagas_oficiais')->nullable();
            $table->integer('vagas_motos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estacionamentos');
    }
};
