<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('alumni')->after('email')->index();
            }

            if (! Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role')->index();
            }

            if (! Schema::hasColumn('users', 'is_approved')) {
                $table->boolean('is_approved')->default(false)->after('is_active')->index();
            }

            if (! Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('password');
            }

            if (! Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            }

            if (! Schema::hasColumn('users', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable()->after('last_login_ip');
            }

            if (! Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // REAL-WORLD READY: Existing starter users need a role and approval state
        // so upgraded installations can boot without a fresh reset.
        DB::table('users')
            ->whereNull('role')
            ->update([
                'role' => 'alumni',
                'is_active' => true,
                'is_approved' => true,
            ]);

        DB::table('users')
            ->where('email', 'admin@alumniportal.com')
            ->update([
                'role' => 'admin',
                'is_active' => true,
                'is_approved' => true,
            ]);
    }

    public function down(): void
    {
        // Intentionally left non-destructive because this migration upgrades
        // an existing starter schema in-place.
    }
};
