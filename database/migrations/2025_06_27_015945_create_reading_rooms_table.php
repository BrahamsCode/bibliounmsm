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
        Schema::create('reading_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ej: "Sala de Lectura General", "Sala de Posgrado"
            $table->string('code', 10)->unique(); // Ej: "SG01", "SP02"
            $table->text('description')->nullable();
            $table->integer('capacity'); // Número de asientos
            $table->string('floor'); // Ej: "Piso 1", "Sótano"
            $table->enum('type', ['general', 'postgraduate', 'research', 'group_study', 'silent', 'computer'])->default('general');
            $table->json('amenities')->nullable(); // ["wifi", "power_outlets", "computers", "projector"]
            $table->time('opening_time')->default('07:00:00');
            $table->time('closing_time')->default('22:00:00');
            $table->boolean('requires_reservation')->default(false);
            $table->enum('status', ['available', 'occupied', 'maintenance', 'closed'])->default('available');
            $table->text('rules')->nullable(); // Reglas específicas de la sala
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_rooms');
    }
};
