<?php

namespace Database\Seeders;

use App\Models\Transactions;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransactionTableSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        Transactions::query()->delete();

        $transactions = [];
        $paymentMethods = ['credit_card', 'paypal', 'crypto', 'bank_transfer', 'wallet'];
        $paymentTypes = ['subscription', 'tip', 'content_purchase', 'chargeback'];
        $statuses = ['pending', 'completed', 'failed', 'refunded'];
        $currencies = ['USD'];
        $contentTypes = ['Music', 'Art', 'Photography', 'Gaming'];

        // Generate 25 transactions
        for ($i = 1; $i <= 25; $i++) {
            $amount = rand(5, 500) + (rand(0, 99) / 100); // Random amount between 5.00 and 500.99
            $platformFee = round($amount * 0.2, 2); // 20% platform fee

            // Pick payment type first to keep consistency in items description
            $paymentType = $paymentTypes[array_rand($paymentTypes)];
            $status = $paymentType === 'chargeback' ? 'refunded' : $statuses[array_rand($statuses)];
            $contentType = $contentTypes[array_rand($contentTypes)];

            $transactions[] = [
                'spender_id' => rand(1, 10), // Assuming 10 spenders exist
                'creator_id' => rand(1, 5), // Assuming 5 creators exist
                'transaction_id' => strtoupper(uniqid()),
                'amount' => $amount,
                'currency' => $currencies[array_rand($currencies)],
                'platform_fee' => $platformFee,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'payment_gateway' => 'stripe',
                'payment_type' => $paymentType,
                'status' => $status,
                'content_type' => $contentType,
                'items' => json_encode([
                    'item_id' => rand(1000, 9999),
                    'description' => $this->generateItemDescription($paymentType),
                    'quantity' => 1,
                    'content_type' => $contentType,
                ]),
                'processed_at' => Carbon::now()->subDays(rand(0, 30)),
                'refunded_at' => $status === 'refunded'
                    ? Carbon::now()->subDays(rand(0, 10))
                    : null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        Transactions::insert($transactions);
        $this->command->info('Successfully seeded 25 transaction records!');
    }

    protected function generateItemDescription(string $paymentType): string
    {
        $descriptions = [
            'subscription' => [
                '1-month premium access',
                'Annual creator membership',
                'VIP fan subscription'
            ],
            'tip' => [
                'Appreciation tip',
                'Support donation',
                'Shoutout payment'
            ],
            'content_purchase' => [
                'Exclusive video bundle',
                'Private photo collection',
                'Premium digital download'
            ],
            'chargeback' => [
                'Subscription refund',
                'Failed transaction reversal',
                'Duplicate charge correction'
            ]
        ];

        return $descriptions[$paymentType][array_rand($descriptions[$paymentType])] ?? 'Transaction item';
    }
}
