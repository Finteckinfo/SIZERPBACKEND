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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Core Transaction Data
            $table->foreignId('spender_id')->constrained('spenders');    // spender paying
            $table->foreignId('creator_id')->constrained('creators');
            $table->string('transaction_id')->unique();                   // Payment gateway transaction ID

            // Financial Details
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->decimal('platform_fee', 12, 2)->default(0);
            $table->decimal('creator_earnings', 12, 2)->virtualAs('amount - platform_fee');
            $table->string('payment_method');                             // credit_card, crypto, wallet, etc.
            $table->string('payment_gateway');                            // stripe, paypal, etc.

            // Transaction Metadata
            $table->string('payment_type');                               // subscription, tip, content_purchase, refund
            $table->string('status');                                     // pending, completed, failed, refunded
            $table->json('items')->nullable();                            // Purchased items/details
            $table->string('content_type')->nullable();                   // e.g. Music, Art, etc.

            // Timestamps
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index('spender_id');
            $table->index('creator_id');
            $table->index('status');
            $table->index('processed_at');
            $table->index('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
