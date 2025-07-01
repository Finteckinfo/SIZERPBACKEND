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
        Schema::create('spenders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creators_id')->constrained('creators')->onDelete('cascade');
            $table->string('name');
            $table->string('username')->unique();
            $table->decimal('total_spent_gross', 12, 2)->default(0);
            $table->decimal('vat', 12, 2)->default(0);
            $table->decimal('platform_fee', 12, 2)->default(0);     
            $table->decimal('total_spent_net', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function creator()
    {
        return $this->belongsTo(Creators::class);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spenders');
    }
};
