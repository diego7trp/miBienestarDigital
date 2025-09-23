<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     */
    public function up(): void
    {
        Schema::create('consejos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');            // título del consejo
            $table->text('contenido');           // contenido del consejo

            // Relación con especialistas
            $table->unsignedBigInteger('especialista_id')->nullable();
            $table->foreign('especialista_id')
                  ->references('id')
                  ->on('especialistas')
                  ->onDelete('cascade');

            $table->timestamps();                // created_at y updated_at
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('consejos');
    }
};


