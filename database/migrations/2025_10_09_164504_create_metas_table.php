<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario')->onDelete('cascade');
            
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', ['pendiente', 'en_progreso', 'completada', 'cancelada'])->default('pendiente');
            $table->integer('progreso')->default(0);
            $table->enum('categoria', ['salud', 'ejercicio', 'mental', 'habitos', 'otro'])->default('otro');
            $table->enum('prioridad', ['baja', 'media', 'alta'])->default('media');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('metas');
    }
};