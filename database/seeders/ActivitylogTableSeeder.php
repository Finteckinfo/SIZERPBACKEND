<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivityLogTableSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        DB::table('activity_logs')->truncate();

        // Sample activity log data
        $activities = [
            [
                'time' => Carbon::now()->subMinutes(30),
                'type' => 'user',
                'event' => 'login',
                'performed_on' => 'User ID: 1',
                'performed_by' => 'System',
                'details' => json_encode(['ip' => '192.168.1.1', 'device' => 'Chrome/Windows']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'time' => Carbon::now()->subHours(2),
                'type' => 'content',
                'event' => 'create',
                'performed_on' => 'Post ID: 45',
                'performed_by' => 'User ID: 2',
                'details' => json_encode(['title' => 'New Tutorial', 'category' => 'Education']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'time' => Carbon::now()->subHours(5),
                'type' => 'payment',
                'event' => 'processed',
                'performed_on' => 'Transaction ID: XF-7890',
                'performed_by' => 'System',
                'details' => json_encode(['amount' => 49.99, 'method' => 'credit_card']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'time' => Carbon::now()->subDays(1),
                'type' => 'settings',
                'event' => 'update',
                'performed_on' => 'Profile Settings',
                'performed_by' => 'User ID: 3',
                'details' => json_encode(['changed' => ['avatar', 'bio']]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'time' => Carbon::now()->subDays(2),
                'type' => 'moderation',
                'event' => 'flag',
                'performed_on' => 'Comment ID: 128',
                'performed_by' => 'User ID: 4',
                'details' => json_encode(['reason' => 'spam', 'moderator' => 'AI System']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'time' => Carbon::now()->subDays(3),
                'type' => 'system',
                'event' => 'backup',
                'performed_on' => 'Database',
                'performed_by' => 'Cron Job',
                'details' => json_encode(['size' => '2.5GB', 'duration' => '15 minutes']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'time' => Carbon::now()->subDays(5),
                'type' => 'user',
                'event' => 'registration',
                'performed_on' => 'New User ID: 56',
                'performed_by' => 'System',
                'details' => json_encode(['plan' => 'premium', 'referral' => 'google_ads']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'time' => Carbon::now()->subWeek(),
                'type' => 'content',
                'event' => 'delete',
                'performed_on' => 'Video ID: 12',
                'performed_by' => 'Admin ID: 1',
                'details' => json_encode(['reason' => 'copyright_claim', 'dmca_case' => 'C-2023-0456']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'time' => Carbon::now()->subWeeks(2),
                'type' => 'api',
                'event' => 'limit_exceeded',
                'performed_on' => 'API Client ID: XZ-890',
                'performed_by' => 'System',
                'details' => json_encode(['endpoint' => '/v1/posts', 'requests' => 1024]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'time' => Carbon::now()->subMonth(),
                'type' => 'security',
                'event' => 'brute_force',
                'performed_on' => 'Login Page',
                'performed_by' => 'IP: 94.23.45.67',
                'details' => json_encode(['attempts' => 23, 'banned' => true]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        // Insert data
        DB::table('activity_logs')->insert($activities);

        $this->command->info('Successfully seeded 10 activity log records!');
    }
}
