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
        Schema::create('daily_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('creators')->onDelete('cascade');
            $table->string('month');
            $table->string('shift_goal_date_range');
            $table->decimal('shift_goal', 10, 2);
            $table->decimal('percentage_increase', 5, 2);
            $table->integer('number_of_shifts');
            $table->decimal('daily_goal', 10, 2);
            $table->timestamps();

            // Add indexes for better performance
            $table->index(['creator_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_goals');
    }
};
