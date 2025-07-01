<?php

namespace Database\Seeders;

use App\Models\Spenders;
use Illuminate\Database\Seeder;
use App\Models\Creators;
use Carbon\Carbon;

class SpendersTableSeeder extends Seeder
{
    public function run()
    {
        // Create multiple creators for testing
        $creators = Creators::factory()->count(8)->create();
        $creatorIds = $creators->pluck('id')->toArray();

        $records = [
            // Original records
            [
                'name' => 'Jowiw Irung',
                'username' => 'johweoe',
                'total_spent_gross' => 150.50,
                'created_at' => '2014-03-15',
            ],
            [
                'name' => 'Mwangi Smmith',
                'username' => 'wsmith',
                'total_spent_gross' => 200.75,
                'created_at' => '2014-07-22',
            ],
            [
                'name' => 'Jaaon Sith',
                'username' => 'jasdsdmith',
                'total_spent_gross' => 320.90,
                'created_at' => '2014-11-08',
            ],
            [
                'name' => 'Jaded Sith',
                'username' => 'janddsmith',
                'total_spent_gross' => 500.00,
                'created_at' => '2023-01-12',
            ],
            [
                'name' => 'Alice Johnson',
                'username' => 'alicej',
                'total_spent_gross' => 275.30,
                'created_at' => '2023-02-18',
            ],
            [
                'name' => 'Robert Chen',
                'username' => 'robchen',
                'total_spent_gross' => 420.85,
                'created_at' => '2023-03-25',
            ],
            [
                'name' => 'Sarah Williams',
                'username' => 'sarahw',
                'total_spent_gross' => 180.60,
                'created_at' => '2014-05-10',
            ],
            [
                'name' => 'Michael Brown',
                'username' => 'mikebrown',
                'total_spent_gross' => 350.45,
                'created_at' => '2014-08-14',
            ],
            [
                'name' => 'Emily Davis',
                'username' => 'emilyd',
                'total_spent_gross' => 225.75,
                'created_at' => '2023-04-30',
            ],
            [
                'name' => 'David Wilson',
                'username' => 'davidw',
                'total_spent_gross' => 480.20,
                'created_at' => '2023-05-16',
            ],
            [
                'name' => 'Lisa Garcia',
                'username' => 'lisag',
                'total_spent_gross' => 315.90,
                'created_at' => '2014-09-03',
            ],
            [
                'name' => 'James Miller',
                'username' => 'jamesm',
                'total_spent_gross' => 190.40,
                'created_at' => '2014-12-20',
            ],
            [
                'name' => 'Maria Rodriguez',
                'username' => 'mariar',
                'total_spent_gross' => 395.65,
                'created_at' => '2023-06-08',
            ],
            [
                'name' => 'Thomas Anderson',
                'username' => 'thomas_a',
                'total_spent_gross' => 280.15,
                'created_at' => '2023-07-14',
            ],
            [
                'name' => 'Jennifer Lee',
                'username' => 'jenlee',
                'total_spent_gross' => 445.80,
                'created_at' => '2014-04-28',
            ],
            [
                'name' => 'Christopher Moore',
                'username' => 'chrismoore',
                'total_spent_gross' => 210.35,
                'created_at' => '2014-10-12',
            ],
            [
                'name' => 'Amanda Taylor',
                'username' => 'amandaT',
                'total_spent_gross' => 365.70,
                'created_at' => '2023-08-22',
            ],
            [
                'name' => 'Matthew Jackson',
                'username' => 'mattjack',
                'total_spent_gross' => 155.25,
                'created_at' => '2023-09-05',
            ],
            [
                'name' => 'Jessica White',
                'username' => 'jessicaw',
                'total_spent_gross' => 520.45,
                'created_at' => '2014-06-17',
            ],
            [
                'name' => 'Daniel Harris',
                'username' => 'danharris',
                'total_spent_gross' => 290.60,
                'created_at' => '2023-10-11',
            ],
            [
                'name' => 'Ashley Martin',
                'username' => 'ashleym',
                'total_spent_gross' => 375.85,
                'created_at' => '2023-11-26',
            ],
            [
                'name' => 'Kevin Thompson',
                'username' => 'kevint',
                'total_spent_gross' => 240.30,
                'created_at' => '2014-01-09',
            ],
            [
                'name' => 'Nicole Clark',
                'username' => 'nicolec',
                'total_spent_gross' => 405.55,
                'created_at' => '2023-12-15',
            ],
            [
                'name' => 'Ryan Lewis',
                'username' => 'ryanl',
                'total_spent_gross' => 185.75,
                'created_at' => '2014-02-26',
            ],

            // Additional records for more comprehensive testing
            [
                'name' => 'Sophie Turner',
                'username' => 'sophiet',
                'total_spent_gross' => 675.30,
                'created_at' => '2024-01-15',
            ],
            [
                'name' => 'Brandon Carter',
                'username' => 'brandcarter',
                'total_spent_gross' => 125.90,
                'created_at' => '2024-02-08',
            ],
            [
                'name' => 'Natalie Kim',
                'username' => 'natkim',
                'total_spent_gross' => 890.45,
                'created_at' => '2024-03-12',
            ],
            [
                'name' => 'Carlos Mendez',
                'username' => 'carlosm',
                'total_spent_gross' => 340.75,
                'created_at' => '2024-04-20',
            ],
            [
                'name' => 'Rachel Green',
                'username' => 'rachelg',
                'total_spent_gross' => 255.60,
                'created_at' => '2024-05-03',
            ],
            [
                'name' => 'Xavier Lopez',
                'username' => 'xavierL',
                'total_spent_gross' => 720.85,
                'created_at' => '2024-06-14',
            ],
            [
                'name' => 'Isabella Rose',
                'username' => 'bellarose',
                'total_spent_gross' => 195.40,
                'created_at' => '2024-07-22',
            ],
            [
                'name' => 'Trevor Scott',
                'username' => 'trevorsc',
                'total_spent_gross' => 465.20,
                'created_at' => '2024-08-11',
            ],
            [
                'name' => 'Maya Patel',
                'username' => 'mayapatel',
                'total_spent_gross' => 310.95,
                'created_at' => '2024-09-18',
            ],
            [
                'name' => 'Jordan Blake',
                'username' => 'jordanb',
                'total_spent_gross' => 580.75,
                'created_at' => '2024-10-05',
            ],
            [
                'name' => 'Victoria Stone',
                'username' => 'vicstone',
                'total_spent_gross' => 245.30,
                'created_at' => '2024-11-12',
            ],
            [
                'name' => 'Ethan Cooper',
                'username' => 'ethanc',
                'total_spent_gross' => 425.65,
                'created_at' => '2024-12-01',
            ],
            [
                'name' => 'Grace Mitchell',
                'username' => 'gracem',
                'total_spent_gross' => 165.80,
                'created_at' => '2025-01-08',
            ],
            [
                'name' => 'Lucas Rivera',
                'username' => 'lucasr',
                'total_spent_gross' => 780.20,
                'created_at' => '2025-01-25',
            ],
            [
                'name' => 'Chloe Adams',
                'username' => 'chloeA',
                'total_spent_gross' => 335.45,
                'created_at' => '2022-03-15',
            ],
            [
                'name' => 'Nathan Price',
                'username' => 'nathanp',
                'total_spent_gross' => 455.90,
                'created_at' => '2022-06-22',
            ],
            [
                'name' => 'Zoe Campbell',
                'username' => 'zoecam',
                'total_spent_gross' => 615.75,
                'created_at' => '2022-09-10',
            ],
            [
                'name' => 'Aaron Hughes',
                'username' => 'aaronh',
                'total_spent_gross' => 290.35,
                'created_at' => '2022-12-18',
            ],
            [
                'name' => 'Lily Watson',
                'username' => 'lilyw',
                'total_spent_gross' => 385.60,
                'created_at' => '2021-04-12',
            ],
            [
                'name' => 'Connor Bailey',
                'username' => 'connorb',
                'total_spent_gross' => 520.80,
                'created_at' => '2021-07-28',
            ],
            [
                'name' => 'Mia Foster',
                'username' => 'miafoster',
                'total_spent_gross' => 175.25,
                'created_at' => '2021-10-14',
            ],
            [
                'name' => 'Blake Reed',
                'username' => 'blakereed',
                'total_spent_gross' => 695.45,
                'created_at' => '2020-02-29',
            ],
            [
                'name' => 'Aria Collins',
                'username' => 'ariac',
                'total_spent_gross' => 265.70,
                'created_at' => '2020-08-16',
            ],
            [
                'name' => 'Tyler Murphy',
                'username' => 'tylerm',
                'total_spent_gross' => 445.15,
                'created_at' => '2020-11-23',
            ],
            [
                'name' => 'Hazel Brooks',
                'username' => 'hazelb',
                'total_spent_gross' => 355.90,
                'created_at' => '2019-01-17',
            ],
            [
                'name' => 'Caleb Ward',
                'username' => 'calebw',
                'total_spent_gross' => 825.30,
                'created_at' => '2019-05-04',
            ],
            [
                'name' => 'Ruby Torres',
                'username' => 'rubyt',
                'total_spent_gross' => 215.85,
                'created_at' => '2019-08-21',
            ],
            [
                'name' => 'Owen Sanders',
                'username' => 'owens',
                'total_spent_gross' => 565.40,
                'created_at' => '2018-03-30',
            ],
            [
                'name' => 'Scarlett Gray',
                'username' => 'scarlettg',
                'total_spent_gross' => 305.75,
                'created_at' => '2018-09-07',
            ],
            [
                'name' => 'Ian Powell',
                'username' => 'ianp',
                'total_spent_gross' => 475.20,
                'created_at' => '2018-12-13',
            ],
            [
                'name' => 'Nova Jenkins',
                'username' => 'novaj',
                'total_spent_gross' => 185.95,
                'created_at' => '2017-06-25',
            ],
            [
                'name' => 'Diego Perry',
                'username' => 'diegop',
                'total_spent_gross' => 745.60,
                'created_at' => '2017-11-11',
            ],
            [
                'name' => 'Luna Richardson',
                'username' => 'lunar',
                'total_spent_gross' => 325.45,
                'created_at' => '2016-02-14',
            ],
            [
                'name' => 'Finn Stewart',
                'username' => 'finns',
                'total_spent_gross' => 655.80,
                'created_at' => '2016-07-08',
            ],
            [
                'name' => 'Sage Morris',
                'username' => 'sagem',
                'total_spent_gross' => 295.35,
                'created_at' => '2015-04-22',
            ],
            [
                'name' => 'Atlas Cook',
                'username' => 'atlasc',
                'total_spent_gross' => 535.70,
                'created_at' => '2015-09-16',
            ],
        ];

        foreach ($records as $record) {
            $gross = $record['total_spent_gross'];
            $vat = round($gross * 0.16, 2);
            $fee = round($gross * 0.10, 2);
            $net = round($gross - $vat - $fee, 2);

            // Randomly assign a creator ID from the available creators
            $randomCreatorId = $creatorIds[array_rand($creatorIds)];

            Spenders::create([
                'creators_id' => $randomCreatorId,
                'name' => $record['name'],
                'username' => $record['username'],
                'total_spent_gross' => $gross,
                'vat' => $vat,
                'platform_fee' => $fee,
                'total_spent_net' => $net,
                'created_at' => Carbon::parse($record['created_at']),
                'updated_at' => Carbon::parse($record['created_at']),
            ]);
        }
    }
}
