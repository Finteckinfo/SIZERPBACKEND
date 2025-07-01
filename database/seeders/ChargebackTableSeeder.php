<?php

namespace Database\Seeders;

use App\Models\Chargebacks;
use App\Models\Spenders;
use App\Models\Transactions;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ChargebackTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Instantiate Faker for fake data
        $faker = Faker::create();

        // Generate 10 chargebacks
        for ($i = 0; $i < 10; $i++) {
            // Randomly pick a spender and creator for the chargeback
            $spender = Spenders::inRandomOrder()->first();  // Get a random spender
            $transactionIds = Transactions::pluck('transaction_id')->toArray();

            // Insert chargeback data
            Chargebacks::create([
                'spender_id' => $spender->id,  // The spender making the chargeback
                'transaction_id' => $transactionIds[array_rand($transactionIds)],
                'amount' => $faker->randomFloat(2, 20, 500),  // Random amount for chargeback
                'currency' => 'USD',  // Fixed currency (could be dynamic if needed)
                'reason' => $faker->sentence,  // Random reason for chargeback
                'status' => $faker->randomElement(['pending', 'resolved', 'reversed']),  // Random chargeback status
                'payment_gateway' => $faker->randomElement(['Stripe', 'PayPal', 'CreditCard']),  // Random gateway
                'gateway_reference' => $faker->uuid,  // Random reference for the gateway
                'submitted_at' => now()->subDays(rand(1, 30)),  // Random submitted date within the last 30 days
                'resolved_at' => $faker->optional()->dateTimeBetween('-10 days', 'now'),  // Optional resolved date
            ]);
        }
    }
}
