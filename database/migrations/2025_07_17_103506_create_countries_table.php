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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 2)->unique(); // Codice ISO a 2 lettere (IT, FR, DE, etc.)
            $table->string('flag_url')->nullable(); // URL della bandiera
            $table->string('capital')->nullable(); // Capitale del paese
            $table->string('continent')->nullable(); // Continente
            $table->integer('difficulty_level')->default(1); // Livello di difficoltà (1-5)
            $table->boolean('is_active')->default(true); // Se il paese è attivo nel quiz
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
