<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Leads captured by the "Apply Now" multi-step qualification form.
     * Stores the raw answers plus the BANT assessment so the admin can
     * review, filter (HOT/WARM/COLD) and follow up later.
     */
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            // Step 1 — goal
            $table->string('goal'); // test_prep, study_abroad, work_abroad

            // Step 2 — tailored details (vary by goal, all nullable)
            $table->string('test')->nullable();
            $table->string('target_score')->nullable();
            $table->string('exam_deadline')->nullable();
            $table->string('education_level')->nullable();
            $table->string('course_of_interest')->nullable();
            $table->string('target_countries')->nullable();
            $table->string('highest_qualification')->nullable();
            $table->string('has_passport')->nullable();
            $table->string('experience_years')->nullable();
            $table->string('current_occupation')->nullable();
            $table->string('target_sector')->nullable();

            // Step 3 — BANT qualification (raw answers)
            $table->string('timeline')->nullable();
            $table->string('budget_status')->nullable();
            $table->string('funding_source')->nullable();
            $table->string('commitment')->nullable();

            // Step 4 — contact
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('contact_method')->nullable();
            $table->text('notes')->nullable();

            // Computed assessment (willing & able to buy)
            $table->string('rating')->default('NEW'); // HOT, WARM, COLD
            $table->unsignedTinyInteger('score')->default(0); // 0-12
            $table->boolean('willing')->default(false);
            $table->boolean('able')->default(false);
            $table->unsignedTinyInteger('willing_score')->default(0); // 0-6
            $table->unsignedTinyInteger('able_score')->default(0); // 0-6
            $table->text('verdict')->nullable();

            // Full label => answer transcript for easy display later
            $table->json('transcript')->nullable();

            // Admin workflow
            $table->string('status')->default('new'); // new, contacted, qualified, won, lost

            $table->timestamps();

            $table->index('rating');
            $table->index('status');
            $table->index('goal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
