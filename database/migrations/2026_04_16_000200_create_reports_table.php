<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->enum('report_type', ['employment_rate', 'job_trends', 'alumni_distribution', 'employer_activity'])->index();
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('parameters')->nullable();
            $table->json('result_data')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('format', ['pdf', 'excel', 'json'])->default('json');
            $table->timestamp('generated_at')->useCurrent()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
