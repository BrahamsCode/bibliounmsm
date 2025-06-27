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
        Schema::create('digital_resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['ebook', 'journal', 'database', 'thesis', 'article', 'video', 'audio', 'dataset'])->default('ebook');
            $table->string('url');
            $table->string('provider')->nullable(); // Ej: "JSTOR", "SciELO", "ProQuest"
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->json('authors')->nullable(); // ["Autor 1", "Autor 2"]
            $table->string('language')->default('español');
            $table->date('publication_date')->nullable();
            $table->string('doi')->nullable(); // Digital Object Identifier
            $table->string('issn')->nullable(); // Para revistas
            $table->enum('access_level', ['public', 'students', 'faculty', 'postgraduate', 'restricted'])->default('students');
            $table->json('allowed_faculties')->nullable(); // IDs de facultades con acceso
            $table->boolean('requires_vpn')->default(false);
            $table->integer('download_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->string('file_format')->nullable(); // "PDF", "EPUB", "MP4"
            $table->bigInteger('file_size')->nullable(); // En bytes
            $table->date('license_expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('access_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_resources');
    }
};
