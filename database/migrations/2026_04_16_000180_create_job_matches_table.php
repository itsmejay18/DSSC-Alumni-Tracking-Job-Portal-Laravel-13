<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->cascadeOnDelete();
            $table->foreignId('alumni_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('match_score', 5, 2)->default(0);
            $table->json('match_reasons')->nullable();
            $table->boolean('is_viewed')->default(false);
            $table->boolean('is_applied')->default(false);
            $table->timestamp('created_at')->useCurrent()->index();

            $table->unique(['job_id', 'alumni_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_matches');
    }
};
