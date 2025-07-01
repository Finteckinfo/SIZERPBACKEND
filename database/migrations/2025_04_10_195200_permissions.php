<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->string('role')->primary();  // 'role' is the primary key and string type
            $table->boolean('access_fans_list')->default(false);
            $table->boolean('view_fan_profiles')->default(false);
            $table->boolean('edit_fan_details')->default(false);
            $table->boolean('ban_fans')->default(false);
            $table->boolean('access_fans_settings')->default(false);
            $table->boolean('disconnect_session')->default(false);
            $table->boolean('mass_message_stats')->default(false);
            $table->boolean('send_mass_messages')->default(false);
            $table->boolean('access_chats')->default(false);
            $table->boolean('delete_messages')->default(false);
            $table->boolean('access_profile')->default(false);
            $table->boolean('verify_creators')->default(false);
            $table->boolean('suspend_creators')->default(false);
            $table->boolean('edit_creator_tiers')->default(false);
            $table->boolean('createedit_posts')->default(false);
            $table->boolean('access_posts')->default(false);
            $table->boolean('approve_posts')->default(false);
            $table->boolean('delete_posts')->default(false);
            $table->boolean('access_streaming')->default(false);
            $table->boolean('access_vault')->default(false);
            $table->boolean('access_queues')->default(false);
            $table->boolean('access_payments')->default(false);
            $table->boolean('process_refunds')->default(false);
            $table->boolean('adjust_creator_payouts')->default(false);
            $table->boolean('access_transactions')->default(false);
            $table->boolean('void_transactions')->default(false);
            $table->boolean('access_chargebacks')->default(false);
            $table->boolean('dispute_chargebacks')->default(false);
            $table->boolean('resolve_chargebacks')->default(false);
            $table->boolean('access_notifications')->default(false);
            $table->boolean('send_system_notifications')->default(false);
            $table->boolean('access_reports')->default(false);
            $table->boolean('export_reports')->default(false);
            $table->boolean('view_analytics')->default(false);
            $table->boolean('access_settings')->default(false);
            $table->boolean('manage_team')->default(false);
            $table->boolean('manage_roles')->default(false);
            $table->boolean('view_audit_logs')->default(false);
            $table->boolean('toggle_system_features')->default(false);
            $table->boolean('impersonate_users')->default(false);
            $table->boolean('emergency_access')->default(false);

            // Timestamps
            $table->timestamps();  // 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
};
