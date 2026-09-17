<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Updates school application service prices to $125.00 (GHS 1,500).
     */
    public function up(): void
    {
        DB::table('services')
            ->where('category', 'school_application')
            ->update(['price' => 125.00]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore original prices
        DB::table('services')
            ->where('slug', 'undergraduate-placement')
            ->update(['price' => 150.00]);

        DB::table('services')
            ->where('slug', 'postgraduate-phd-consulting')
            ->update(['price' => 250.00]);
    }
};
