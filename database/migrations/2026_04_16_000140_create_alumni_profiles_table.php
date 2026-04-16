<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('student_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('contact_number', 30)->nullable();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('year_graduated')->nullable()->index();
            $table->date('graduation_date')->nullable();
            $table->json('skills')->nullable();
            $table->enum('employment_status', ['employed', 'unemployed', 'self-employed', 'further_study', 'not_looking'])->default('unemployed')->index();
            $table->string('current_job_title')->nullable();
            $table->string('current_company')->nullable();
            $table->decimal('current_salary', 12, 2)->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->boolean('is_verified')->default(false)->index();
            $table->timestamp('verification_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_profiles');
    }
};
