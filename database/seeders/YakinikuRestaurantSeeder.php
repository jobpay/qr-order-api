<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Menu\Category;
use App\Models\Menu\MenuItem;
use App\Models\Menu\MenuItemOption;
use App\Models\Menu\MenuItemOptionValue;
use App\Models\Order\Order;
use App\Models\Order\OrderOption;
use App\Models\Sale;
use App\Models\Seat;
use App\Models\Store\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class YakinikuRestaurantSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Store::create([
            'id' => 2,
            'name' => '焼肉レストラン牛太郎',
            'category_id' => 1,
        ]);

        User::create([
            'name' => 'yakiniku',
            'email' => 'yakiniku@example.com',
            'password' => Hash::make('password'),
            'store_id' => 2,
            'role_id' => 1,
        ]);

        Category::insert([
            ['id' => 1, 'store_id' => 2, 'name' => '牛肉', 'order' => 1],
            ['id' => 2, 'store_id' => 2, 'name' => '豚肉・鶏肉', 'order' => 2],
            ['id' => 3, 'store_id' => 2, 'name' => 'ホルモン', 'order' => 3],
            ['id' => 4, 'store_id' => 2, 'name' => '海鮮', 'order' => 4],
            ['id' => 5, 'store_id' => 2, 'name' => '野菜', 'order' => 5],
            ['id' => 6, 'store_id' => 2, 'name' => 'セット', 'order' => 6],
            ['id' => 7, 'store_id' => 2, 'name' => 'サイドメニュー', 'order' => 7],
            ['id' => 8, 'store_id' => 2, 'name' => 'デザートメニュー', 'order' => 8],
            ['id' => 9, 'store_id' => 2, 'name' => 'ドリンクメニュー', 'order' => 9],
        ]);

        $menuItems = [
            1 => [
                ['name' => 'カルビ', 'description' => '脂ののった柔らかい部位'],
                ['name' => 'ハラミ', 'description' => '赤身の旨みが詰まった部位'],
                ['name' => 'ロース', 'description' => '脂控えめで柔らかい肉'],
                ['name' => 'タン', 'description' => 'サクサクした食感の牛の舌'],
                ['name' => 'ヒレ', 'description' => '最も柔らかい部位、脂が少ない'],
                ['name' => 'ミスジ', 'description' => '希少部位で柔らかい肉'],
                ['name' => 'サーロイン', 'description' => '肉質がきめ細かく柔らかい'],
                ['name' => 'ザブトン', 'description' => '霜降りが美しい柔らかい肉'],
                ['name' => 'ランプ', 'description' => '赤身と脂が程よいバランス'],
                ['name' => 'イチボ', 'description' => '赤身の中で最も甘みが強い部位'],
            ],
            2 => [
                ['name' => '豚バラ', 'description' => '脂が多めでジューシーな部位'],
                ['name' => '鶏もも', 'description' => 'しっかりした歯ごたえのある部位'],
                ['name' => '豚肩ロース', 'description' => '脂身が適度に入り、柔らかい'],
                ['name' => '鶏ハツ', 'description' => 'コリコリとした食感の心臓部'],
                ['name' => '鶏レバー', 'description' => '鉄分豊富でクリーミーな味わい'],
                ['name' => '豚トロ', 'description' => '脂肪が多く、ジューシーな部位'],
                ['name' => '鶏ナンコツ', 'description' => 'コリコリした独特の食感'],
                ['name' => '鶏手羽', 'description' => 'ジューシーな鶏の翼の部分'],
                ['name' => '鶏ささみ', 'description' => '脂肪が少なくヘルシーな部位'],
                ['name' => '豚ヒレ', 'description' => '柔らかく脂肪が少ない部位'],
            ],
            3 => [
                ['name' => 'ミノ', 'description' => '歯ごたえのある牛の胃の一部'],
                ['name' => 'ハツ', 'description' => 'コリコリとした心臓部'],
                ['name' => 'レバー', 'description' => '栄養豊富でクリーミーな味わい'],
                ['name' => 'センマイ', 'description' => '独特の食感のある胃袋'],
                ['name' => 'シマチョウ', 'description' => '脂がたっぷりで濃厚なホルモン'],
                ['name' => 'テッチャン', 'description' => '歯ごたえと脂のバランスが良い'],
                ['name' => 'マルチョウ', 'description' => 'ジューシーな脂の多い部位'],
                ['name' => 'ギアラ', 'description' => 'しっかりとした食感の胃袋'],
                ['name' => 'フワ', 'description' => '柔らかくて軽い食感のホルモン'],
                ['name' => 'ハチノス', 'description' => '噛み応えのある牛の胃の部位'],
            ],
            4 => [
                ['name' => 'イカ', 'description' => '柔らかくて風味豊かな海鮮'],
                ['name' => 'エビ', 'description' => 'プリプリとした食感'],
                ['name' => 'ホタテ', 'description' => '甘みが強く肉厚な貝'],
                ['name' => 'タコ', 'description' => '噛み応えのある海鮮'],
                ['name' => '牡蠣', 'description' => '濃厚でクリーミーな味わい'],
                ['name' => 'ホッケ', 'description' => '脂がのった白身魚'],
                ['name' => 'サバ', 'description' => '脂がたっぷり乗った青魚'],
                ['name' => '鮭', 'description' => '風味豊かなピンクの魚'],
                ['name' => 'マグロ', 'description' => '濃厚で赤身が特徴の魚'],
                ['name' => 'サーモン', 'description' => '脂ののったオレンジ色の魚'],
            ],
            5 => [
                ['name' => 'キャベツ', 'description' => 'シャキシャキとした食感の葉野菜'],
                ['name' => 'ナス', 'description' => '焼くととろける柔らかい野菜'],
                ['name' => 'ピーマン', 'description' => '香ばしい風味の野菜'],
                ['name' => '玉ねぎ', 'description' => '甘みのある香り豊かな野菜'],
                ['name' => 'とうもろこし', 'description' => '甘くてジューシーな野菜'],
                ['name' => 'じゃがいも', 'description' => 'ホクホクとした食感'],
                ['name' => 'にんじん', 'description' => '甘くて柔らかい根菜'],
                ['name' => 'しいたけ', 'description' => '旨みの強いきのこ'],
                ['name' => 'ししとう', 'description' => 'ピリッとした辛さのある野菜'],
                ['name' => 'アスパラガス', 'description' => 'シャキシャキとした食感の茎野菜'],
            ],
            6 => [
                ['name' => '焼肉セット', 'description' => '色々な肉が楽しめるセット'],
                ['name' => 'ファミリーセット', 'description' => '家族向けのお得なセット'],
                ['name' => 'お得セット', 'description' => 'バラエティ豊富なセット'],
                ['name' => 'レディースセット', 'description' => '女性に人気の少量セット'],
                ['name' => 'お子様セット', 'description' => '子供向けの小さなセット'],
                ['name' => '牛肉セット', 'description' => '牛肉のみが楽しめるセット'],
                ['name' => '豚肉セット', 'description' => '豚肉がたっぷりのセット'],
                ['name' => '海鮮セット', 'description' => '新鮮な海鮮が入ったセット'],
                ['name' => '野菜セット', 'description' => '新鮮な野菜がいっぱいのセット'],
                ['name' => '特選セット', 'description' => '厳選された高級な肉のセット'],
            ],
            7 => [
                ['name' => 'ライス', 'description' => 'ふっくら炊き上がった白米'],
                ['name' => 'キムチ', 'description' => 'ピリ辛の漬物'],
                ['name' => 'ナムル', 'description' => '野菜を塩とごま油で和えた料理'],
                ['name' => '冷奴', 'description' => '冷たい豆腐を使ったシンプルな料理'],
                ['name' => 'サラダ', 'description' => '新鮮な野菜を使ったサラダ'],
                ['name' => 'ビビンバ', 'description' => '石焼きで提供される混ぜご飯'],
                ['name' => 'クッパ', 'description' => '韓国風のスープご飯'],
                ['name' => 'ユッケジャン', 'description' => '辛くて旨みのある韓国風スープ'],
                ['name' => 'わかめスープ', 'description' => 'わかめの風味が広がるスープ'],
                ['name' => '玉子スープ', 'description' => '卵が入ったまろやかなスープ'],
            ],
            8 => [
                ['name' => 'アイスクリーム', 'description' => '冷たくて甘いデザート'],
                ['name' => 'シャーベット', 'description' => 'さっぱりとした氷菓'],
                ['name' => 'プリン', 'description' => '滑らかで甘いデザート'],
                ['name' => 'ケーキ', 'description' => 'ふわふわで甘いデザート'],
                ['name' => '和菓子', 'description' => '日本の伝統的な甘味'],
                ['name' => 'ゼリー', 'description' => '弾力のある甘いデザート'],
                ['name' => 'フルーツポンチ', 'description' => 'フルーツを使った冷たいデザート'],
                ['name' => 'あんみつ', 'description' => '黒蜜をかけた和風デザート'],
                ['name' => 'ぜんざい', 'description' => '温かい小豆の甘味'],
                ['name' => 'たい焼き', 'description' => '中にあんこが詰まった焼き菓子'],
            ],
            9 => [
                ['name' => 'ビール', 'description' => '冷たい発泡酒'],
                ['name' => '焼酎', 'description' => '蒸留酒の一種で、米や麦を使用'],
                ['name' => '日本酒', 'description' => '米を発酵させた伝統的な酒'],
                ['name' => 'ワイン', 'description' => '葡萄から作られる発酵酒'],
                ['name' => 'ウーロン茶', 'description' => 'すっきりした風味の中国茶'],
                ['name' => 'コーラ', 'description' => '炭酸の効いた甘い飲み物'],
                ['name' => 'ジンジャーエール', 'description' => 'ショウガの風味が効いた炭酸飲料'],
                ['name' => 'カルピス', 'description' => '乳酸菌を使った甘い飲み物'],
                ['name' => 'オレンジジュース', 'description' => 'オレンジを絞ったジュース'],
                ['name' => '緑茶', 'description' => '香ばしい味わいの日本茶'],
            ],
        ];

        foreach ($menuItems as $categoryId => $items) {
            foreach ($items as $index => $item) {
                MenuItem::create([
                    'store_id' => 2,
                    'category_id' => $categoryId,
                    'number' => $index + 1,
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'price' => rand(300, 1000),
                ]);
            }
        }
    }
}
