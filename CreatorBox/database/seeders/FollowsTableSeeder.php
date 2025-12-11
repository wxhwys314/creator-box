<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FollowsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('follows')->insert([
            [
                'follower_id' => 1,
                'following_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'follower_id' => 1,
                'following_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'follower_id' => 1,
                'following_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'follower_id' => 16,
                'following_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'follower_id' => 16,
                'following_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'follower_id' => 16,
                'following_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // [
            //     'follower_id' => XX,     // Regular user ID
            //     'following_id' => XX,    // Creator ID
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
        ]);
    }
}