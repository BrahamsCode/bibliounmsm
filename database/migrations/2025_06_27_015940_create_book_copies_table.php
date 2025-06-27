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
        Schema::create('book_copies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->string('barcode')->unique();
            $table->integer('copy_number');
            $table->enum('status', ['available', 'loaned', 'reserved', 'maintenance', 'damaged', 'lost'])->default('available');
            $table->string('location')->nullable(); // Ej: "Estante A-5, Piso 2"
            $table->text('condition_notes')->nullable();
            $table->date('acquisition_date')->nullable();
            $table->decimal('acquisition_cost', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_copies');
    }
};
