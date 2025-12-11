<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Creator users
            [
                'name' => 'Mika Pikazo',
                'email' => 'test@creator1.com',
                'password' => 'Welkom123!',
                'role' => 'creator',
                'creator_id' => 'mikapikazo',
                'avatar' => 'avatars/mika-pikazo_avatar.jpeg',
                'bio' => 'Hello,My name is Mika Pikazo.
I’d like to share the secret stories of my works, the process of making them, and
the public some of the things I’ve been working on.
I would like to create more works, challenge expressions, and announce them.
Your support will be used for future presentations, exhibitions, equipment and interviews.
Thank you very much!',
                'cover' => 'covers/mika-pikazo_cover.jpeg',
                'coin_balance' => 114,
            ],
            [
                'name' => 'シエラ',
                'email' => 'test@creator2.com',
                'password' => 'Welkom123!',
                'role' => 'creator',
                'creator_id' => 'cierra',
                'avatar' => 'avatars/cierra_avatar.png',
                'bio' => 'I’m シエラ, a Japanese illustrator best known for creating artwork for the rhythm game Arcaea. I love bringing fantasy worlds and detailed characters to life, blending light, shadow, and emotion in my illustrations.',
                'cover' => 'covers/cierra_cover.jpg',
                'coin_balance' => 514,
            ],
            [
                'name' => 'oO大黄Oo',
                'email' => 'test@creator3.com',
                'password' => 'Welkom123!',
                'role' => 'creator',
                'creator_id' => 'oodahuangoo',
                'avatar' => 'avatars/oodahuangoo_avatar.png',
                'bio' => 'I’m oO大黄Oo, a Chinese illustrator passionate about vibrant fantasy art. I often create fan works inspired by Touhou Project alongside game‑related illustrations, blending rich colors and detailed characters to bring imaginative worlds to life.',
                'cover' => 'covers/53031871_p0.jpg',
                'coin_balance' => 320,
            ],
            [
                'name' => 'Saclia',
                'email' => 'test@creator4.com',
                'password' => 'Welkom123!',
                'role' => 'creator',
                'creator_id' => 'saclia',
                'avatar' => 'avatars/saclia_avatar.png',
                'bio' => 'I’m Saclia, a Chinese illustrator and animation student. I love creating dreamy, colorful artworks that blend light and shadow to capture emotion and fantasy.',
                'cover' => 'covers/saclia_cover.png',
                'coin_balance' => 320,
            ],
            [
                'name' => '羽々斬',
                'email' => 'test@creator5.com',
                'password' => 'Welkom123!',
                'role' => 'creator',
                'creator_id' => 'uuzan',
                'avatar' => 'avatars/uuzan_avatar.png',
                'bio' => 'I’m 羽々斬, a Japanese illustrator best known for my Touhou Project fan art. I love creating dramatic, fantasy‑inspired works with rich light and shadow to bring characters to life.',
                'cover' => 'covers/uuzan_cover.png',
                'coin_balance' => 320,
            ],
            [
                'name' => 'MiyU',
                'email' => 'test@creator6.com',
                'password' => 'Welkom123!',
                'role' => 'creator',
                'creator_id' => 'miyu',
                'avatar' => 'avatars/miyu_avatar.png',
                'bio' => 'email : miy_u1308@naver.com',
                'cover' => 'covers/miyu_cover.png',
                'coin_balance' => 320,
            ],
            [
                'name' => '果坂青/omao',
                'email' => 'test@creator7.com',
                'password' => 'Welkom123!',
                'role' => 'creator',
                'creator_id' => 'omao',
                'avatar' => 'avatars/omao_avatar.png',
                'bio' => '白と黒が好きなイラストレーター',
                'cover' => 'covers/omao_cover.png',
                'coin_balance' => 320,
            ],
            [
                'name' => 'kajatony',
                'email' => 'test@creator8.com',
                'password' => 'Welkom123!',
                'role' => 'creator',
                'creator_id' => 'kajatony',
                'avatar' => 'avatars/kajatony_avatar.png',
                'bio' => '',
                'cover' => 'covers/kajatony_cover.jpg',
                'coin_balance' => 320,
            ],
            [
                'name' => 'ぢせ',
                'email' => 'test@creator9.com',
                'password' => 'Welkom123!',
                'role' => 'creator',
                'creator_id' => 'psychoron',
                'avatar' => 'avatars/psychoron_avatar.png',
                'bio' => 'こいしちゃんが好きです',
                'cover' => 'covers/psychoron_cover.png',
                'coin_balance' => 320,
            ],
            [
                'name' => 'キツネイロ',
                'email' => 'test@creator10.com',
                'password' => 'Welkom123!',
                'role' => 'creator',
                'creator_id' => 'kitsuneiro',
                'avatar' => 'avatars/kitsuneiro_avatar.jpg',
                'bio' => '準和風',
                'cover' => 'covers/kitsuneiro_cover.png',
                'coin_balance' => 320,
            ],
            [
                'name' => 'Jazz Jack',
                'email' => 'test@creator11.com',
                'password' => 'Welkom123!',
                'role' => 'creator',
                'creator_id' => 'jazzjack',
                'avatar' => 'avatars/jazzjack_avatar.png',
                'bio' => '',
                'cover' => 'covers/jazzjack_cover.jpg',
                'coin_balance' => 320,
            ],
            [
                'name' => 'ゆゆはる',
                'email' => 'test@creator12.com',
                'password' => 'Welkom123!',
                'role' => 'creator',
                'creator_id' => 'fyufyuharu',
                'avatar' => 'avatars/fyufyuharu_avatar.png',
                'bio' => 'ご覧頂きありがとうございます。透明水彩やデジタルでイラストを描いています。',
                'cover' => 'covers/fyufyuharu_cover.png',
                'coin_balance' => 320,
            ],
            [
                'name' => 'まころんコミケ107南g20a',
                'email' => 'test@creator13.com',
                'password' => 'Welkom123!',
                'role' => 'creator',
                'creator_id' => 'makoron',
                'avatar' => 'avatars/makoron_avatar.png',
                'bio' => '',
                'cover' => 'covers/makoron_cover.jpg',
                'coin_balance' => 320,
            ],
            [
                'name' => '東京幻想',
                'email' => 'test@creator14.com',
                'password' => 'Welkom123!',
                'role' => 'creator',
                'creator_id' => 'tokyogenso',
                'avatar' => 'avatars/tokyogenso_avatar.png',
                'bio' => '',
                'cover' => 'covers/tokyogenso_cover.png',
                'coin_balance' => 320,
            ],
            [
                'name' => 'こもりひっき',
                'email' => 'test@creator15.com',
                'password' => 'Welkom123!',
                'role' => 'creator',
                'creator_id' => 'hikkikomori',
                'avatar' => 'avatars/hikkikomori_avatar.png',
                'bio' => '',
                'cover' => 'covers/hikkikomori_cover.jpg',
                'coin_balance' => 320,
            ],

            // Fan users
            [
                'name' => 'Tomotake Yoshino',
                'email' => 'test@fan1.com',
                'password' => 'Welkom123!',
                'role' => 'fan',
                'creator_id' => null,
                'avatar' => null,
                'bio' => 'This is the bio of fan.',
                'coin_balance' => 114,
            ],
            [
                'name' => 'Murasame Sama',
                'email' => 'test@fan2.com',
                'password' => 'Welkom123!',
                'role' => 'fan',
                'creator_id' => null,
                'avatar' => null,
                'bio' => 'This is the bio of fan.',
                'coin_balance' => 514,
            ],
            [
                'name' => 'Hitachi Mako',
                'email' => 'test@fan3.com',
                'password' => 'Welkom123!',
                'role' => 'fan',
                'creator_id' => null,
                'avatar' => null,
                'bio' => 'This is the bio of fan.',
                'coin_balance' => 1919,
            ],
            [
                'name' => 'Shirayuki Noa',
                'email' => 'test@fan4.com',
                'password' => 'Welkom123!',
                'role' => 'fan',
                'creator_id' => null,
                'avatar' => null,
                'bio' => 'This is the bio of fan.',
                'coin_balance' => 810,
            ],
            [
                'name' => 'Tanikaze Amane',
                'email' => 'test@fan5.com',
                'password' => 'Welkom123!',
                'role' => 'fan',
                'creator_id' => null,
                'avatar' => null,
                'bio' => 'This is the bio of fan.',
                'coin_balance' => 5000,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}