<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->cascadeOnDelete();
            $table->foreignId('alumni_id')->constrained('users')->cascadeOnDelete();
            $table->longText('cover_letter');
            $table->string('resume_path');
            $table->string('portfolio_link')->nullable();
            $table->date('availability_date')->nullable();
            $table->decimal('expected_salary', 12, 2)->nullable();
            $table->enum('status', ['pending', 'shortlisted', 'interviewed', 'accepted', 'rejected', 'hired'])->default('pending')->index();
            $table->foreignId('status_updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('status_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['job_id', 'alumni_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
