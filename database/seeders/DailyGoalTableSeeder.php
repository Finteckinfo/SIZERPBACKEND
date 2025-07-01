<?php

namespace Database\Seeders;

use App\Models\DailyGoal;
use App\Models\Creators;
use Illuminate\Database\Seeder;

class DailyGoalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all existing creators
        $creators = Creators::all();

        // Define some sample months
        $months = [
            'January 2024',
            'February 2024',
            'March 2024',
            'April 2024',
            'May 2024',
            'June 2024',
        ];

        // Define sample date ranges
        $dateRanges = [
            '1-7',
            '8-14',
            '15-21',
            '22-28',
            '1-15',
            '16-31',
        ];

        foreach ($creators as $creator) {
            // Create 3-5 daily goals per creator
            $goalCount = rand(3, 5);

            for ($i = 0; $i < $goalCount; $i++) {
                $shiftGoal = rand(500, 2000);
                $percentageIncrease = rand(5, 25);
                $numberOfShifts = rand(10, 30);

                // Calculate daily goal based on shift goal and number of shifts
                $dailyGoal = round($shiftGoal / $numberOfShifts, 2);

                DailyGoal::create([
                    'creator_id' => $creator->id,
                    'month' => $months[array_rand($months)],
                    'shift_goal_date_range' => $dateRanges[array_rand($dateRanges)],
                    'shift_goal' => $shiftGoal,
                    'percentage_increase' => $percentageIncrease,
                    'number_of_shifts' => $numberOfShifts,
                    'daily_goal' => $dailyGoal,
                ]);
            }
        }

        $this->command->info('Daily goals seeded successfully!');
    }
}
