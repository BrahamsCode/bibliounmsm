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
        Schema::create('academic_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Ej: "Ingeniería de Sistemas"
            $table->string('code', 10)->unique(); // Ej: "IS", "MED", "DER"
            $table->text('description')->nullable();
            $table->enum('degree_type', ['bachelor', 'master', 'doctorate', 'technical'])->default('bachelor');
            $table->integer('duration_semesters'); // Ej: 10 semestres
            $table->string('coordinator_name')->nullable();
            $table->string('coordinator_email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_programs');
    }
};
