<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sponsorship;
use Carbon\Carbon;

class SponsorshipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sponsorships = [
            [
                'supporter_id' => 16,
                'plan_id' => 1,
                'creator_id' => 1,
                'monthly_amount' => 100,
                'status' => 'active',
                'cancelled_at' => null,
                'expires_at' => Carbon::now()->endOfMonth(),
            ],
            [
                'supporter_id' => 16,
                'plan_id' => 4,
                'creator_id' => 2,
                'monthly_amount' => 150,
                'status' => 'active',
                'cancelled_at' => null,
                'expires_at' => Carbon::now()->endOfMonth(),
            ],
            [
                'supporter_id' => 16,
                'plan_id' => 7,
                'creator_id' => 3,
                'monthly_amount' => 200,
                'status' => 'cancelled',
                'cancelled_at' => Carbon::now(),
                'expires_at' => Carbon::now()->endOfMonth(),
            ],
            [
                'supporter_id' => 17,
                'plan_id' => 10,
                'creator_id' => 4,
                'monthly_amount' => 100,
                'status' => 'active',
                'cancelled_at' => null,
                'expires_at' => Carbon::now()->endOfMonth(),
            ],
            [
                'supporter_id' => 17,
                'plan_id' => 12,
                'creator_id' => 5,
                'monthly_amount' => 150,
                'status' => 'active',
                'cancelled_at' => null,
                'expires_at' => Carbon::now()->endOfMonth(),
            ],
            [
                'supporter_id' => 17,
                'plan_id' => 14,
                'creator_id' => 6,
                'monthly_amount' => 200,
                'status' => 'cancelled',
                'cancelled_at' => Carbon::now(),
                'expires_at' => Carbon::now()->endOfMonth(),
            ],
            // [
            //     'supporter_id' => XX,
            //     'plan_id' => XX,
            //     'creator_id' => XX,
            //     'monthly_amount' => XX,                         // min: 100
            //     'status' => 'active',                            // 'active' || 'cancelled'
            //     'cancelled_at' => null,                          // active ?? null :: Carbon::now()
            //     'expires_at' => Carbon::now()->endOfMonth(),
            // ],
        ];

        foreach ($sponsorships as $sponsorship) {
            Sponsorship::create($sponsorship);
        }
    }
}