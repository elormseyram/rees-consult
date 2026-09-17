<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Admin-editable automated emails. Each row is keyed by the event that
     * triggers it (see App\Services\AutomatedMailer::EVENTS).
     */
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();       // lead.welcome, service_signup.confirmation, ...
            $table->string('name');                // human label in the admin UI
            $table->string('description')->nullable();
            $table->string('subject');
            $table->text('body');                  // HTML, with {{ placeholder }} tokens
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
