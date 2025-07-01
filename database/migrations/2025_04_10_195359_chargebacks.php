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
        Schema::create('chargebacks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('spender_id');
            $table->string('transaction_id'); // foreign to transactions.transaction_id
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('reason')->nullable();
            $table->string('status')->default('pending'); // e.g., pending, resolved
            $table->string('payment_gateway');
            $table->string('gateway_reference')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->foreign('spender_id')->references('id')->on('spenders')->onDelete('cascade');
            $table->foreign('transaction_id')->references('transaction_id')->on('transactions')->onDelete('cascade');

            $table->index('transaction_id');
            $table->index('spender_id');
            $table->index('status');
        });
    }

     /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('chargebacks');
    }
};
