<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('creators', function (Blueprint $table) {
            $table->id();
            $table->string('profile')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('creator_type', ['Direct Pay', 'Non-Direct Pay']);
            $table->date('on_board_date')->nullable();
            $table->date('off_board_date')->nullable();
            $table->string('google_sheet_name');
            $table->string('google_sheet_name_new_template');
            $table->enum('account_group', ['FreePages', 'Default']);
            $table->boolean('on_platform')->default(false);
            $table->date('on_platform_date')->nullable();
            $table->date('off_platform_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('archived')->default(false);
            $table->string('content_type')->nullable(); // newly added
            $table->timestamp('last_active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creators');
    }
};
