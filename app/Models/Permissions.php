<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permissions extends Model
{
    use HasFactory;

    protected $primaryKey = 'role';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'role',
        'access_fans_list',
        'view_fan_profiles',
        'edit_fan_details',
        'ban_fans',
        'access_fans_settings',
        'disconnect_session',
        'mass_message_stats',
        'send_mass_messages',
        'access_chats',
        'delete_messages',
        'access_profile',
        'verify_creators',
        'suspend_creators',
        'edit_creator_tiers',
        'createedit_posts',
        'access_posts',
        'approve_posts',
        'delete_posts',
        'access_streaming',
        'access_vault',
        'access_queues',
        'access_payments',
        'process_refunds',
        'adjust_creator_payouts',
        'access_transactions',
        'void_transactions',
        'access_chargebacks',
        'dispute_chargebacks',
        'resolve_chargebacks',
        'access_notifications',
        'send_system_notifications',
        'access_reports',
        'export_reports',
        'view_analytics',
        'access_settings',
        'manage_team',
        'manage_roles',
        'view_audit_logs',
        'toggle_system_features',
        'impersonate_users',
        'emergency_access'
    ];

    protected $casts = [
        // Cast all boolean fields
        'access_fans_list' => 'boolean',
        'view_fan_profiles' => 'boolean',
        'edit_fan_details' => 'boolean',
        'ban_fans' => 'boolean',
        'access_fans_settings' => 'boolean',
        'disconnect_session' => 'boolean',
        'mass_message_stats' => 'boolean',
        'send_mass_messages' => 'boolean',
        'access_chats' => 'boolean',
        'delete_messages' => 'boolean',
        'access_profile' => 'boolean',
        'verify_creators' => 'boolean',
        'suspend_creators' => 'boolean',
        'edit_creator_tiers' => 'boolean',
        'createedit_posts' => 'boolean',
        'access_posts' => 'boolean',
        'approve_posts' => 'boolean',
        'delete_posts' => 'boolean',
        'access_streaming' => 'boolean',
        'access_vault' => 'boolean',
        'access_queues' => 'boolean',
        'access_payments' => 'boolean',
        'process_refunds' => 'boolean',
        'adjust_creator_payouts' => 'boolean',
        'access_transactions' => 'boolean',
        'void_transactions' => 'boolean',
        'access_chargebacks' => 'boolean',
        'dispute_chargebacks' => 'boolean',
        'resolve_chargebacks' => 'boolean',
        'access_notifications' => 'boolean',
        'send_system_notifications' => 'boolean',
        'access_reports' => 'boolean',
        'export_reports' => 'boolean',
        'view_analytics' => 'boolean',
        'access_settings' => 'boolean',
        'manage_team' => 'boolean',
        'manage_roles' => 'boolean',
        'view_audit_logs' => 'boolean',
        'toggle_system_features' => 'boolean',
        'impersonate_users' => 'boolean',
        'emergency_access' => 'boolean',

        // Cast the timestamps as datetime
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
