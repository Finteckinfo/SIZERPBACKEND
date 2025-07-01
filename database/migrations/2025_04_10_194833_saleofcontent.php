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
        Schema::create('saleofcontent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creators_id')->constrained('creators');
            $table->foreignId('fan_id')->constrained('spenders');
            $table->string('content_purchased');
            $table->decimal('amount_paid', 10, 2);
            $table->string('sale_status');
            $table->string('fulfilment_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saleofcontent');
    }
};
