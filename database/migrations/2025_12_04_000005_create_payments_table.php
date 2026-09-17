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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->nullable()->constrained()->onDelete('set null');
            $table->string('payment_method'); // mobile_money, bank_transfer, etc.
            $table->string('provider')->nullable(); // MTN, Vodafone, AirtelTigo
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('GHS');
            $table->string('reference_number')->nullable();
            $table->string('screenshot')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
