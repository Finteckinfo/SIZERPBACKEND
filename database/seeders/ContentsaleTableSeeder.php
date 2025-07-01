<?php

namespace Database\Seeders;

use App\Models\SaleofContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContentsaleTableSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        DB::table('saleofcontent')->truncate();

        // Sample content sales data
        $sales = [
            [
                'creators_id' => 1,
                'fan_id' => 1,
                'content_purchased' => 'Exclusive Video Bundle',
                'amount_paid' => 49.99,
                'sale_status' => 'completed',
                'fulfilment_status' => 'delivered',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5)
            ],
            [
                'creators_id' => 2,
                'fan_id' => 3,
                'content_purchased' => 'Premium Photo Set',
                'amount_paid' => 19.99,
                'sale_status' => 'completed',
                'fulfilment_status' => 'viewed',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(2)
            ],
            [
                'creators_id' => 1,
                'fan_id' => 2,
                'content_purchased' => 'Custom Audio Message',
                'amount_paid' => 9.99,
                'sale_status' => 'pending',
                'fulfilment_status' => 'processing',
                'created_at' => Carbon::now()->subDay(),
                'updated_at' => Carbon::now()->subHour()
            ],
            [
                'creators_id' => 3,
                'fan_id' => 1,
                'content_purchased' => 'Monthly Subscription',
                'amount_paid' => 14.99,
                'sale_status' => 'refunded',
                'fulfilment_status' => 'revoked',
                'created_at' => Carbon::now()->subWeek(),
                'updated_at' => Carbon::now()->subDays(4)
            ],
            [
                'creators_id' => 2,
                'fan_id' => 4,
                'content_purchased' => 'Live Stream Access',
                'amount_paid' => 24.99,
                'sale_status' => 'completed',
                'fulfilment_status' => 'expired',
                'created_at' => Carbon::now()->subMonth(),
                'updated_at' => Carbon::now()->subMonth()
            ],
            [
                'creators_id' => 1,
                'fan_id' => 1,
                'content_purchased' => 'Exclusive Video Bundle',
                'amount_paid' => 249.99,
                'sale_status' => 'completed',
                'fulfilment_status' => 'delivered',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5)
            ],
        ];

        // Insert data
        DB::table('saleofcontent')->insert($sales);

        $this->command->info('Successfully seeded 5 content sales records!');
    }
}
