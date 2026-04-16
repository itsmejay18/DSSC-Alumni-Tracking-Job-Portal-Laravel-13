<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->longText('requirements')->nullable();
            $table->longText('responsibilities')->nullable();
            $table->longText('qualifications')->nullable();
            $table->decimal('salary_min', 12, 2)->nullable();
            $table->decimal('salary_max', 12, 2)->nullable();
            $table->enum('salary_type', ['monthly', 'yearly', 'hourly', 'project'])->default('monthly');
            $table->string('location')->nullable();
            $table->boolean('is_remote')->default(false);
            $table->enum('job_type', ['full-time', 'part-time', 'contract', 'freelance', 'internship'])->default('full-time')->index();
            $table->enum('experience_level', ['entry', 'junior', 'senior', 'lead'])->default('entry')->index();
            $table->string('education_requirement')->nullable();
            $table->json('skills_required')->nullable();
            $table->date('application_deadline')->nullable()->index();
            $table->unsignedInteger('max_applicants')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'closed', 'expired'])->default('pending')->index();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('applications_count')->default(0);
            $table->boolean('is_featured')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
