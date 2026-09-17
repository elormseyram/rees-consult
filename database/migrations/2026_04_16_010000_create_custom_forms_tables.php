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
        Schema::create('custom_forms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('bg_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('custom_form_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_form_id')->constrained()->onDelete('cascade');
            $table->string('type'); // text, textarea, radio, checkbox, select
            $table->string('question_text');
            $table->boolean('is_required')->default(false);
            $table->json('options')->nullable(); // For keeping multiple choices
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });

        Schema::create('custom_form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_form_id')->constrained()->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

        Schema::create('custom_form_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_form_submission_id')->constrained()->onDelete('cascade');
            $table->foreignId('custom_form_question_id')->constrained()->onDelete('cascade');
            $table->text('answer')->nullable(); // Storing JSON string arrays for checkboxes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_form_answers');
        Schema::dropIfExists('custom_form_submissions');
        Schema::dropIfExists('custom_form_questions');
        Schema::dropIfExists('custom_form_scopes'); // Typo avoidance
        Schema::dropIfExists('custom_forms');
    }
};
