<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE registrations MODIFY COLUMN status VARCHAR(50) DEFAULT 'pending'");
    }

    public function down(): void
    {
        //
    }
};