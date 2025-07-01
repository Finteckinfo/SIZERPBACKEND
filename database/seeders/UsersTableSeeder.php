<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        DB::table('users')->delete();

        $users = [
            // Admin account
            [
                'fullname' => 'Admin Test',
                'email' => 'admin@example.com',
                'password' => Hash::make('UserPass789!'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Moderator account
            [
                'fullname' => 'Kipruto R',
                'email' => 'kipruto@example.com',
                'password' => Hash::make('Pa$$w0rd!'),
                'role' => 'moderator',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Regular users
            [
                'fullname' => 'Mcnub H7Admin',
                'email' => 'mcnub@example.com',
                'password' => Hash::make('UserPass789!'),
                'role' => 'user',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'fullname' => 'Fuzzy B.',
                'email' => 'fuzzy@example.com',
                'password' => Hash::make('UserPass789!'),
                'role' => 'user',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'fullname' => 'Mike Odhiambo',
                'email' => 'mike@example.com',
                'password' => Hash::make('UserPass789!'),
                'role' => 'user',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'fullname' => 'Sarah Williams',
                'email' => 'sarah@example.com',
                'password' => Hash::make('UserPass789!'),
                'role' => 'user',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'fullname' => 'David Brown',
                'email' => 'david@example.com',
                'password' => Hash::make('UserPass789!'),
                'role' => 'user',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'fullname' => 'Lisa Davis',
                'email' => 'lisa@example.com',
                'password' => Hash::make('UserPass789!'),
                'role' => 'user',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'fullname' => 'Robert Wilson',
                'email' => 'robert@example.com',
                'password' => Hash::make('UserPass789!'),
                'role' => 'user',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'fullname' => 'Emily Taylor',
                'email' => 'emily@example.com',
                'password' => Hash::make('UserPass789!'),
                'role' => 'user',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('users')->insert($users);
        $this->command->info('Successfully seeded 10 user records!');
    }
}
