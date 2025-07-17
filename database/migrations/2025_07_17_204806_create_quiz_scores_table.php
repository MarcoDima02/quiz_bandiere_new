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
        Schema::create('quiz_scores', function (Blueprint $table) {
            $table->id();
            $table->string('player_name');
            $table->integer('score');
            $table->integer('total_questions');
            $table->decimal('percentage', 5, 2);
            $table->integer('difficulty_level');
            $table->integer('total_time_seconds');
            $table->integer('avg_time_per_question');
            $table->json('quiz_details')->nullable(); // Dettagli del quiz (risposte, paesi, etc)
            $table->ipAddress('player_ip')->nullable();
            $table->timestamps();
            
            // Indici per performance
            $table->index(['percentage', 'difficulty_level']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_scores');
    }
};
