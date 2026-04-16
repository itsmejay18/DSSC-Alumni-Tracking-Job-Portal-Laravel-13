<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('company_name')->unique();
            $table->string('company_registration_number')->nullable();
            $table->string('company_logo_path')->nullable();
            $table->foreignId('industry_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('company_size', ['1-10', '11-50', '51-200', '201-500', '500+'])->nullable();
            $table->string('website')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->default('Philippines');
            $table->string('postal_code')->nullable();
            $table->json('verification_documents')->nullable();
            $table->boolean('is_verified')->default(false)->index();
            $table->timestamp('verification_date')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employers');
    }
};
