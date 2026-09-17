<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Demographic details captured on the "About you" step of the Apply Now form.
     * All nullable so existing leads stay valid; required at the validation layer.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('sex')->nullable()->after('goal');
            $table->string('age_range')->nullable()->after('sex');
            $table->string('country')->nullable()->after('age_range');
            $table->string('city')->nullable()->after('country');
            $table->string('nationality')->nullable()->after('city');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['sex', 'age_range', 'country', 'city', 'nationality']);
        });
    }
};
