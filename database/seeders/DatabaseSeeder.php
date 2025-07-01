<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            PermissionsTableSeeder::class,
            SpendersTableSeeder::class,
            CreatorsTableSeeder::class,
            ActivitylogTableSeeder::class,
            BrowserKeysTableSeeder::class,
            ContentsaleTableSeeder::class,
            TransactionTableSeeder::class,
            ChargebackTableSeeder::class,
            DailyGoalTableSeeder::class,
        ]);
    }
}
