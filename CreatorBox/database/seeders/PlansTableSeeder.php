<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'creator_id' => 1,
                'plan_name' => 'YELLOW',
                'monthly_amount' => 100,
                'cover' => 'plans/covers/YELLOW.jpeg',
                'description' => '○Recent News and Articles
○Access to posts from the last 3 months
○Sketches and line drawings and Production Story of uploaded Illust',
                'is_active' => true,
            ],
            [
                'creator_id' => 1,
                'plan_name' => 'RED',
                'monthly_amount' => 200,
                'cover' => 'plans/covers/RED.jpeg',
                'description' => '○Access to posts from the last 6 months
○Recent news and articles (with announcement before publication on SNS)
○Sketches and line drawings and Production Story of uploaded illust
○Unpublished Rough and Illust',
                'is_active' => true,
            ],
            [
                'creator_id' => 1,
                'plan_name' => 'BLUE',
                'monthly_amount' => 300,
                'cover' => 'plans/covers/BLUE.jpeg',
                'description' => '○Full access to all the posts
○Recent news and articles (with announcement before publication on SNS)
○Sketches and line drawings and Production Story of uploaded illust
○Unpublished Rough and Illust
○Distribution of time-lapse videos or WIP files',
                'is_active' => true,
            ],
            [
                'creator_id' => 2,
                'plan_name' => 'Sketch Supporter',
                'monthly_amount' => 150,
                'cover' => null,
                'description' => 'Access monthly sketch drops and behind-the-scenes notes from my illustration process.',
                'is_active' => true,
            ],
            [
                'creator_id' => 2,
                'plan_name' => 'Art Insider',
                'monthly_amount' => 250,
                'cover' => null,
                'description' => 'Unlock exclusive timelapse videos, digital wallpapers, and participate in monthly Q&A sessions.',
                'is_active' => true,
            ],
            [
                'creator_id' => 2,
                'plan_name' => 'Illustration VIP',
                'monthly_amount' => 350,
                'cover' => null,
                'description' => 'Receive early access to new artworks, plus one personalized illustration request per year.',
                'is_active' => true,
            ],

            [
                'creator_id' => 3,
                'plan_name' => 'Color Enthusiast',
                'monthly_amount' => 200,
                'cover' => null,
                'description' => 'Get exclusive colored versions of sketches and monthly illustration breakdowns.',
                'is_active' => true,
            ],
            [
                'creator_id' => 3,
                'plan_name' => 'Studio Access',
                'monthly_amount' => 300,
                'cover' => null,
                'description' => 'Watch behind-the-scenes studio videos and vote on upcoming illustration themes.',
                'is_active' => true,
            ],
            [
                'creator_id' => 3,
                'plan_name' => 'Collector’s Tier',
                'monthly_amount' => 400,
                'cover' => null,
                'description' => 'Receive signed digital prints and exclusive concept art packs.',
                'is_active' => true,
            ],

            [
                'creator_id' => 4,
                'plan_name' => 'Wallpaper Pack',
                'monthly_amount' => 100,
                'cover' => null,
                'description' => 'Download monthly illustration wallpapers in HD resolution.',
                'is_active' => true,
            ],
            [
                'creator_id' => 4,
                'plan_name' => 'Illustration Workshop',
                'monthly_amount' => 200,
                'cover' => null,
                'description' => 'Join online workshops where I share tips and critique community artworks.',
                'is_active' => true,
            ],

            [
                'creator_id' => 5,
                'plan_name' => 'Storyboard Reader',
                'monthly_amount' => 150,
                'cover' => null,
                'description' => 'Access exclusive storyboards and narrative illustration drafts.',
                'is_active' => true,
            ],
            [
                'creator_id' => 5,
                'plan_name' => 'Art Salon',
                'monthly_amount' => 250,
                'cover' => null,
                'description' => 'Participate in monthly illustration discussions and receive curated art essays.',
                'is_active' => true,
            ],

            [
                'creator_id' => 6,
                'plan_name' => 'Illustration Drops',
                'monthly_amount' => 200,
                'cover' => null,
                'description' => 'Get exclusive monthly illustration packs with sketches and finished pieces.',
                'is_active' => true,
            ],
            [
                'creator_id' => 6,
                'plan_name' => 'Commission Tier',
                'monthly_amount' => 300,
                'cover' => null,
                'description' => 'Receive priority slots for commissioned illustrations and behind-the-scenes updates.',
                'is_active' => true,
            ],

            [
                'creator_id' => 7,
                'plan_name' => 'Concept Art Explorer',
                'monthly_amount' => 500,
                'cover' => null,
                'description' => 'Gain access to exclusive concept art, character sheets, and creative process notes.',
                'is_active' => true,
            ],

            [
                'creator_id' => 8,
                'plan_name' => 'Masterclass Tier',
                'monthly_amount' => 600,
                'cover' => null,
                'description' => 'Unlock advanced illustration tutorials and receive feedback on your own artworks.',
                'is_active' => true,
            ],

            [
                'creator_id' => 9,
                'plan_name' => 'Art Collector Premium',
                'monthly_amount' => 700,
                'cover' => null,
                'description' => 'Receive exclusive high-resolution art packs, plus limited edition signed digital prints.',
                'is_active' => true,
            ],
            //more plans
            // [
            //     'creator_id' => XXX,
            //     'plan_name' => 'XXX',
            //     'monthly_amount' => XXX,     // min: 100
            //     'cover' => null,             // null || 'plans/covers/XXX'
            //     'description' => null,       // null || 'XXX'
            //     'is_active' => true,
            // ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}