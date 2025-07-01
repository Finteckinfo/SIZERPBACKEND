<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permissions;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        // Predefined permissions data
        $permissions = [
            [
                'role' => 'admin',
                'access_fans_list' => true,
                'view_fan_profiles' => true,
                'edit_fan_details' => true,
                'ban_fans' => true,
                'access_fans_settings' => true,
                'disconnect_session' => true,
                'mass_message_stats' => true,
                'send_mass_messages' => true,
                'access_chats' => true,
                'delete_messages' => true,
                'access_profile' => true,
                'verify_creators' => true,
                'suspend_creators' => true,
                'edit_creator_tiers' => true,
                'createedit_posts' => true,
                'access_posts' => true,
                'approve_posts' => true,
                'delete_posts' => true,
                'access_streaming' => true,
                'access_vault' => true,
                'access_queues' => true,
                'access_payments' => true,
                'process_refunds' => true,
                'adjust_creator_payouts' => true,
                'access_transactions' => true,
                'void_transactions' => true,
                'access_chargebacks' => true,
                'dispute_chargebacks' => true,
                'resolve_chargebacks' => true,
                'access_notifications' => true,
                'send_system_notifications' => true,
                'access_reports' => true,
                'export_reports' => true,
                'view_analytics' => true,
                'access_settings' => true,
                'manage_team' => true,
                'manage_roles' => true,
                'view_audit_logs' => true,
                'toggle_system_features' => true,
                'impersonate_users' => true,
                'emergency_access' => true,
            ],
            [
                'role' => 'moderator',
                'access_fans_list' => true,
                'view_fan_profiles' => true,
                'edit_fan_details' => true,
                'ban_fans' => false,
                'access_fans_settings' => false,
                'disconnect_session' => false,
                'mass_message_stats' => true,
                'send_mass_messages' => false,
                'access_chats' => true,
                'delete_messages' => true,
                'access_profile' => true,
                'verify_creators' => false,
                'suspend_creators' => false,
                'edit_creator_tiers' => false,
                'createedit_posts' => true,
                'access_posts' => true,
                'approve_posts' => false,
                'delete_posts' => false,
                'access_streaming' => true,
                'access_vault' => false,
                'access_queues' => true,
                'access_payments' => false,
                'process_refunds' => false,
                'adjust_creator_payouts' => false,
                'access_transactions' => false,
                'void_transactions' => false,
                'access_chargebacks' => false,
                'dispute_chargebacks' => false,
                'resolve_chargebacks' => false,
                'access_notifications' => true,
                'send_system_notifications' => false,
                'access_reports' => true,
                'export_reports' => false,
                'view_analytics' => false,
                'access_settings' => false,
                'manage_team' => false,
                'manage_roles' => false,
                'view_audit_logs' => false,
                'toggle_system_features' => false,
                'impersonate_users' => false,
                'emergency_access' => false,
            ]
            // You can add more roles here like "creator", "viewer", etc.
        ];

        // Loop through each permissions array and insert them into the database
        foreach ($permissions as $permission) {
            Permissions::create($permission);
        }
    }
}
