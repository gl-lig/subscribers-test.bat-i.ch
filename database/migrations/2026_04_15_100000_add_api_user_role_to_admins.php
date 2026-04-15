<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // api_user role is now supported via string column - no schema change needed
        // The role column already accepts any string value including 'api_user'
    }

    public function down(): void
    {
        // Nothing to reverse
    }
};
