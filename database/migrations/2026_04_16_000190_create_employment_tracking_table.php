<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employment_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('users')->cascadeOnDelete();
            $table->string('previous_status')->nullable();
            $table->string('new_status')->nullable();
            $table->string('previous_company')->nullable();
            $table->string('new_company')->nullable();
            $table->string('previous_title')->nullable();
            $table->string('new_title')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('change_date')->useCurrent()->index();
            $table->text('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employment_tracking');
    }
};
