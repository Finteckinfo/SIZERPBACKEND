<?php

namespace Database\Seeders;

use App\Models\BrowserKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BrowserKeysTableSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        BrowserKeys::truncate();

        // Sample browser keys data
        $keys = [
            [
                'keys' => json_encode([
                    'api_key' => Str::random(40),
                    'client_id' => 'web-' . Str::random(8),
                    'secret' => Str::random(32)
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'keys' => json_encode([
                    'api_key' => Str::random(40),
                    'client_id' => 'mobile-' . Str::random(8),
                    'secret' => Str::random(32)
                ]),
                'created_at' => Carbon::now()->subDay(),
                'updated_at' => Carbon::now()->subDay()
            ],
            [
                'keys' => json_encode([
                    'api_key' => Str::random(40),
                    'client_id' => 'extension-' . Str::random(8),
                    'expires_at' => Carbon::now()->addYear()->toDateTimeString()
                ]),
                'created_at' => Carbon::now()->subWeek(),
                'updated_at' => Carbon::now()->subWeek()
            ],
            [
                'keys' => json_encode([
                    'api_key' => Str::random(40),
                    'client_id' => 'partner-' . Str::random(8),
                    'permissions' => ['read_only']
                ]),
                'created_at' => Carbon::now()->subMonth(),
                'updated_at' => Carbon::now()->subMonth()
            ],
            [
                'keys' => json_encode([
                    'api_key' => Str::random(40),
                    'client_id' => 'internal-' . Str::random(8),
                    'permissions' => ['full_access'],
                    'ip_whitelist' => ['192.168.1.1']
                ]),
                'created_at' => Carbon::now()->subYear(),
                'updated_at' => Carbon::now()->subYear()
            ]
        ];

        BrowserKeys::insert($keys);
        $this->command->info('Successfully seeded 5 browser keys records!');
    }
}
