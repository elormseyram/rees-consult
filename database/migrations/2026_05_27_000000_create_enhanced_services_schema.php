<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update Services Table with Category, Country, and Processing Fees
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'category')) {
                $table->string('category')->default('standardized_test')->after('slug'); // standardized_test, school_application, job_abroad
            }
            if (!Schema::hasColumn('services', 'processing_fee')) {
                $table->decimal('processing_fee', 10, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('services', 'country')) {
                $table->string('country')->nullable()->after('processing_fee'); // UK, Canada, Germany, etc.
            }
        });

        // 2. Create Service Signups Table (For Standardized Tests)
        Schema::create('service_signups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('preferred_class_type'); // group_in_person, group_online, one_on_one_in_person, one_on_one_online
            $table->string('status')->default('pending'); // pending, contacted, active, completed
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Create School Applications Table (For School Application Leads)
        Schema::create('school_applications', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('course_of_interest');
            $table->string('level_of_education'); // Undergraduate, Postgraduate, PhD, Diploma
            $table->string('target_countries'); // Comma-separated list or JSON
            $table->string('highest_qualification');
            $table->boolean('has_passport')->default(false);
            $table->string('budget'); // Under $5k, $5k-$10k, $10k-$20k, $20k+
            $table->string('resume_path')->nullable(); // Optional file upload
            $table->string('transcript_path')->nullable(); // Optional file upload
            $table->string('status')->default('pending'); // pending, reviewing, accepted, closed
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. Create Job Applications Table (For Job Placement Leads)
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade'); // Points to the job listing
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->integer('experience_years');
            $table->string('current_occupation');
            $table->string('highest_education');
            $table->string('resume_path')->nullable(); // Optional file upload
            $table->string('status')->default('pending'); // pending, reviewing, placed, closed
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('school_applications');
        Schema::dropIfExists('service_signups');

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['category', 'processing_fee', 'country']);
        });
    }
};
