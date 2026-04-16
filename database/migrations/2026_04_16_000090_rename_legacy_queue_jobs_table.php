<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // PRODUCTION-READY: Older Laravel installs may already have a queue table
        // named "jobs". We rename it before creating the portal's actual jobs table.
        if (Schema::hasTable('jobs') && ! Schema::hasTable('queued_jobs')) {
            $columns = Schema::getColumnListing('jobs');

            if (in_array('queue', $columns, true) && in_array('payload', $columns, true) && ! in_array('status', $columns, true)) {
                Schema::rename('jobs', 'queued_jobs');
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('queued_jobs') && ! Schema::hasTable('jobs')) {
            $columns = Schema::getColumnListing('queued_jobs');

            if (in_array('queue', $columns, true) && in_array('payload', $columns, true) && ! in_array('status', $columns, true)) {
                Schema::rename('queued_jobs', 'jobs');
            }
        }
    }
};
