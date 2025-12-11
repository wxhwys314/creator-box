<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'user_id' => 1,
                'type' => 'blog',
                'cover' => null,
                'title' => 'Welcome to My First Blog',
                'content_text' => 'In this post I share my first impressions of the platform and my plans for the future.',
                'media_assets' => null,
                'visibility' => 'all',
                'supporter_min_amount' => 0,
                'comment_permission' => 'all',
                'status' => 'published',
                'likes_count' => 0,
                'published_at' => now(),
            ],
            [
                'user_id' => 1,
                'type' => 'blog',
                'cover' => 'posts/covers/113825921_p0.png',
                'title' => 'COMITIA146',
                'content_text' => '12/3(日)のコミティアにて、新刊「MIKA PIKAZO ANIMELABO 2023」発行いたします。

新刊を手に取ってくださった方に会場限定ペーパーを配布いたします。
「あ-15b MikaPikaZo」です！',
                'media_assets' => null,
                'visibility' => 'all',
                'supporter_min_amount' => 0,
                'comment_permission' => 'none',
                'status' => 'published',
                'likes_count' => 0,
                'published_at' => now(),
            ],
            [
                'user_id' => 1,
                'type' => 'image',
                'cover' => 'posts/covers/124748386_p0.png',
                'title' => 'DONALD DUCK',
                'content_text' => 'ドナルドからイメージした女の子ちゃんです。
ポップアップ＆展示イベント＆グッズなど...
「Disney Collection by Mika Pikazo」盛りだくさんです！',
                'media_assets' => [
                    [
                        'path' => 'posts/images/124748386_p0.png',
                        'original_name' => '124748386_p0.png',
                        'size' => 245678,
                        'mime_type' => 'image/png',
                    ],
                    [
                        'path' => 'posts/images/124748386_p1_master1200.jpg',
                        'original_name' => '124748386_p1_master1200.jpg',
                        'size' => 145678,
                        'mime_type' => 'image/jpeg',
                    ],
                    [
                        'path' => 'posts/images/124748386_p2.png',
                        'original_name' => '124748386_p2.png',
                        'size' => 114514,
                        'mime_type' => 'image/png',
                    ],
                ],
                'visibility' => 'supporters',
                'supporter_min_amount' => 100,
                'comment_permission' => 'supporters',
                'status' => 'published',
                'likes_count' => 0,
                'published_at' => now(),
            ],
            [
                'user_id' => 1,
                'type' => 'image',
                'cover' => 'posts/covers/124691127_p0.png',
                'title' => 'MINNIE MOUSE',
                'content_text' => 'ミニーマウスからイメージした女の子ちゃんです。
ポップアップ＆展示イベント＆グッズなど...
「Disney Collection by Mika Pikazo」盛りだくさんです！',
                'media_assets' => [
                    [
                        'path' => 'posts/images/124691127_p0.png',
                        'original_name' => '124691127_p0.png',
                        'size' => 245678,
                        'mime_type' => 'image/png',
                    ],
                    [
                        'path' => 'posts/images/124691127_p1.png',
                        'original_name' => '124691127_p1.png',
                        'size' => 245678,
                        'mime_type' => 'image/png',
                    ],
                    [
                        'path' => 'posts/images/124748386_p2.png',
                        'original_name' => '124748386_p2.png',
                        'size' => 114514,
                        'mime_type' => 'image/png',
                    ],
                ],
                'visibility' => 'supporters',
                'supporter_min_amount' => 200,
                'comment_permission' => 'none',
                'status' => 'published',
                'likes_count' => 0,
                'published_at' => now(),
            ],
            [
                'user_id' => 1,
                'type' => 'file',
                'cover' => null,
                'title' => 'Test Documentation',
                'content_text' => null,
                'media_assets' => [
                    [
                        'path' => 'posts/files/test.txt',
                        'original_name' => 'test.txt',
                        'size' => 12345,
                        'mime_type' => 'text/plain',
                    ],
                ],
                'visibility' => 'supporters',
                'supporter_min_amount' => 300,
                'comment_permission' => 'supporters',
                'status' => 'published',
                'likes_count' => 0,
                'published_at' => now(),
            ],
            [
                'user_id' => 2,
                'type' => 'image',
                'cover' => 'posts/covers/123723791_p0.jpg',
                'title' => '【Counterfeit】',
                'content_text' => '2022年秋のM3にて描かせていただいた藍月なくるさんの7thアルバム、『Counterfeit』のジャケットイラストです。発表当時から2年以上経ってしまいましたが、収録曲もイラストもお気に入りの一枚です。',
                'media_assets' => [
                    [
                        'path' => 'posts/images/123723791_p0.jpg',
                        'original_name' => '123723791_p0.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                ],
                'visibility' => 'all',
                'supporter_min_amount' => 0,
                'comment_permission' => 'all',
                'status' => 'published',
                'likes_count' => 0,
                'published_at' => now(),
            ],
            [
                'user_id' => 2,
                'type' => 'image',
                'cover' => 'posts/covers/89292853_p0.jpg',
                'title' => '【Transpain】',
                'content_text' => '藍月なくるさんの6thアルバム、『Transpain』のジャケットイラストを担当させていただきました！
なくるさんの儚くも力強い歌声が、ガラスが砕けて零れ落ちるような透明な痛みと美しさを感じさせてくれます。
ぜひお手に取っていただけたら嬉しいです╰(*´︶`*)╯♡

M3-2021春（4月25日）にて、第一展示場「P-10」"Endorfin.”さまから頒布されます。',
                'media_assets' => [
                    [
                        'path' => 'posts/images/89292853_p0.jpg',
                        'original_name' => '89292853_p0.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                ],
                'visibility' => 'supporters',
                'supporter_min_amount' => 150,
                'comment_permission' => 'supporters',
                'status' => 'published',
                'likes_count' => 0,
                'published_at' => now(),
            ],
            [
                'user_id' => 2,
                'type' => 'image',
                'cover' => 'posts/covers/67726338_p0.jpg',
                'title' => '【LUNA】',
                'content_text' => '',
                'media_assets' => [
                    [
                        'path' => 'posts/images/67726338_p0.jpg',
                        'original_name' => '67726338_p0.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                ],
                'visibility' => 'supporters',
                'supporter_min_amount' => 250,
                'comment_permission' => 'none',
                'status' => 'published',
                'likes_count' => 0,
                'published_at' => now(),
            ],
            [
                'user_id' => 3,
                'type' => 'image',
                'cover' => 'posts/covers/86247816_p0.jpg',
                'title' => '大黄画的-占星少女',
                'content_text' => '少女占星中........',
                'media_assets' => [
                    [
                        'path' => 'posts/images/86247816_p0.jpg',
                        'original_name' => '86247816_p0.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                ],
                'visibility' => 'all',
                'supporter_min_amount' => 0,
                'comment_permission' => 'all',
                'status' => 'published',
                'likes_count' => 0,
                'published_at' => now(),
            ],
            [
                'user_id' => 3,
                'type' => 'image',
                'cover' => 'posts/covers/76551015_p0.jpg',
                'title' => '针妙丸的到访',
                'content_text' => '有糖果点心和鲜花的静物画',
                'media_assets' => [
                    [
                        'path' => 'posts/images/76551015_p0.jpg',
                        'original_name' => '76551015_p0.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                ],
                'visibility' => 'supporters',
                'supporter_min_amount' => 200,
                'comment_permission' => 'supporters',
                'status' => 'published',
                'likes_count' => 0,
                'published_at' => now(),
            ],
            [
                'user_id' => 3,
                'type' => 'image',
                'cover' => 'posts/covers/61269686_p0.jpg',
                'title' => '愛麗絲的人偶工坊',
                'content_text' => null,
                'media_assets' => [
                    [
                        'path' => 'posts/images/61269686_p0.jpg',
                        'original_name' => '61269686_p0.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                ],
                'visibility' => 'supporters',
                'supporter_min_amount' => 300,
                'comment_permission' => 'none',
                'status' => 'published',
                'likes_count' => 0,
                'published_at' => now(),
            ],
            [
                'user_id' => 4,
                'type' => 'image',
                'cover' => 'posts/covers/135537263_p0.jpg',
                'title' => 'BILIBILI 2233',
                'content_text' => '年初给bilibili绘画区活动画的一套服设和kv.',
                'media_assets' => [
                    [
                        'path' => 'posts/images/135537263_p0.jpg',
                        'original_name' => '135537263_p0.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                    [
                        'path' => 'posts/images/135537263_p1.jpg',
                        'original_name' => '135537263_p1.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                    [
                        'path' => 'posts/images/135537263_p2.jpg',
                        'original_name' => '135537263_p2.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                ],
                'visibility' => 'all',
                'supporter_min_amount' => 0,
                'comment_permission' => 'all',
                'status' => 'published',
                'likes_count' => 0,
                'published_at' => now(),
            ],
            [
                'user_id' => 4,
                'type' => 'image',
                'cover' => 'posts/covers/103323667_p0.jpg',
                'title' => 'THE LOST VISION',
                'content_text' => null,
                'media_assets' => [
                    [
                        'path' => 'posts/images/103323667_p0.jpg',
                        'original_name' => '103323667_p0.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                    [
                        'path' => 'posts/images/103323667_p1.jpg',
                        'original_name' => '103323667_p1.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                    [
                        'path' => 'posts/images/103323667_p2.jpg',
                        'original_name' => '103323667_p2.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                    [
                        'path' => 'posts/images/103323667_p3.jpg',
                        'original_name' => '103323667_p3.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                    [
                        'path' => 'posts/images/103323667_p4.jpg',
                        'original_name' => '103323667_p4.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                    [
                        'path' => 'posts/images/103323667_p5.jpg',
                        'original_name' => '103323667_p5.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                ],
                'visibility' => 'supporters',
                'supporter_min_amount' => 100,
                'comment_permission' => 'supporters',
                'status' => 'published',
                'likes_count' => 0,
                'published_at' => now(),
            ],
            [
                'user_id' => 4,
                'type' => 'image',
                'cover' => 'posts/covers/124757408_p0.jpg',
                'title' => 'THE LOST VISION 2',
                'content_text' => null,
                'media_assets' => [
                    [
                        'path' => 'posts/images/124757408_p0.jpg',
                        'original_name' => '124757408_p0.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                    [
                        'path' => 'posts/images/124757408_p1.jpg',
                        'original_name' => '124757408_p1.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                    [
                        'path' => 'posts/images/124757408_p2.jpg',
                        'original_name' => '124757408_p2.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                    [
                        'path' => 'posts/images/124757408_p3.jpg',
                        'original_name' => '124757408_p3.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                    [
                        'path' => 'posts/images/124757408_p4.jpg',
                        'original_name' => '124757408_p4.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                    [
                        'path' => 'posts/images/124757408_p5.jpg',
                        'original_name' => '124757408_p5.jpg',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                ],
                'visibility' => 'supporters',
                'supporter_min_amount' => 200,
                'comment_permission' => 'supporters',
                'status' => 'published',
                'likes_count' => 0,
                'published_at' => now(),
            ],
            [
                'user_id' => 5,
                'type' => 'image',
                'cover' => 'posts/covers/67329317_p0.png',
                'title' => '羽化',
                'content_text' => '背中の扉を開くとき',
                'media_assets' => [
                    [
                        'path' => 'posts/images/67329317_p0.png',
                        'original_name' => '67329317_p0.png',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                ],
                'visibility' => 'all',
                'supporter_min_amount' => 0,
                'comment_permission' => 'all',
                'status' => 'published',
                'likes_count' => 0,
                'published_at' => now(),
            ],
            [
                'user_id' => 5,
                'type' => 'image',
                'cover' => 'posts/covers/67281051_p0.png',
                'title' => '死体旅行',
                'content_text' => '廃獄ララバイからの死体旅行とてもすき',
                'media_assets' => [
                    [
                        'path' => 'posts/images/67281051_p0.png',
                        'original_name' => '67281051_p0.png',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                ],
                'visibility' => 'supporters',
                'supporter_min_amount' => 150,
                'comment_permission' => 'supporters',
                'status' => 'published',
                'likes_count' => 0,
                'published_at' => now(),
            ],
            [
                'user_id' => 5,
                'type' => 'image',
                'cover' => 'posts/covers/67069522_p0.png',
                'title' => '海底撈月',
                'content_text' => '麻雀の役の中で一番かっこいいと思う',
                'media_assets' => [
                    [
                        'path' => 'posts/images/67069522_p0.png',
                        'original_name' => '67069522_p0.png',
                        'size' => 114514,
                        'mime_type' => 'image/jpeg',
                    ],
                ],
                'visibility' => 'supporters',
                'supporter_min_amount' => 250,
                'comment_permission' => 'none',
                'status' => 'published',
                'likes_count' => 0,
                'published_at' => now(),
            ],
            //more posts
            // [
            //     'user_id' => XXX,
            //     'type' => 'XXX',                     // 'blog' || 'images' || 'files'
            //     'cover' => null,                     // null || 'posts/covers/XXX'
            //     'title' => 'XXX',
            //     'content_text' => null,              // null || 'XXX'
            //     'media_assets' => [],                // null || [])
            //     'visibility' => 'all',               // 'all' || 'supporters'
            //     'supporter_min_amount' => 0,         // min: 100
            //     'comment_permission' => 'all',       // 'all' || 'supporters' || 'none'
            //     'status' => 'published',             // 'published' || 'draft'
            //     'likes_count' => 0,
            //     'published_at' => now(),
            // ],
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}