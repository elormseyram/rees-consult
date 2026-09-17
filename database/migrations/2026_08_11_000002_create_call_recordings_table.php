<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Audio captured in the browser while a staff member speaks to a lead on
     * loudspeaker. Files live on the private disk and are only ever served
     * through an authenticated route.
     */
    public function up(): void
    {
        Schema::create('call_recordings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('recorded_by')->nullable(); // staff name snapshot
            $table->string('disk')->default('local');
            $table->string('path');
            $table->string('mime_type')->nullable();
            $table->unsignedInteger('duration_seconds')->default(0);
            $table->unsignedBigInteger('size_bytes')->default(0);
            $table->text('summary')->nullable();

            // Consent trail — the employee confirms the lead was told the call
            // is being recorded before capture can start.
            $table->boolean('consent_confirmed')->default(false);
            $table->timestamp('consent_confirmed_at')->nullable();

            $table->timestamps();

            $table->index(['lead_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('call_recordings');
    }
};
