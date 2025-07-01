<?php

namespace Database\Seeders;

use App\Models\TeamMembers;
use Illuminate\Database\Seeder;

class TeamMembersTableSeeder extends Seeder
{
    public function run()
    {
        TeamMembers::insert([
            [
                'name' => 'Enock',
                'email' => 'admin@example.com',
                'role' => 'admin',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Add more team members as needed
        ]);
    }
}
