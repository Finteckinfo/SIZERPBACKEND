<?php

namespace Database\Seeders;

use App\Models\Creators;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreatorsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Create 20 creators
        Creators::factory()->count(20)->create();
    }
}
