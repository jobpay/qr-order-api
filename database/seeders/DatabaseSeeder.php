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
use App\Models\Store\StoreCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $store_categories = [
            ['id' => 1, 'name' => 'カフェ'],
            ['id' => 2, 'name' => '美容院・美容室'],
            ['id' => 3, 'name' => 'レストラン'],
            ['id' => 4, 'name' => 'ネイルサロン'],
            ['id' => 5, 'name' => 'バー'],
        ];
        foreach($store_categories as $store_category) {
            StoreCategory::create([
                'id' => $store_category['id'],
                'name' => $store_category['name'],
            ]);
        }

        Store::create([
            'name' => 'admin',
            'category_id' => 1,
        ]);

        User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'store_id' => 1,
            'role_id' => 1,
        ]);

        Category::create([
            'id' => 1,
            'store_id' => 1,
            'name' => '一品',
            'order' => 1,
        ]);

        MenuItem::create([
            'id' => 1,
            'store_id' => 1,
            'category_id' => 1,
            'number' => 1,
            'name' => 'ハンバーガー',
            'price' => 500,
        ]);

        MenuItemOption::create([
            'id' => 1,
            'menu_item_id' => 1,
            'name' => 'ソース',
        ]);

        $menu_item_option_values = [
            [
                'id' => 1,
                'menu_item_option_id' => 1,
                'order' => 1,
                'cost' => 120,
                'value' => 'ケチャップ',
            ],
            [
                'id' => 2,
                'menu_item_option_id' => 1,
                'order' => 2,
                'cost' => 150,
                'value' => 'マスタード',
            ],
        ];

        foreach ($menu_item_option_values as $menu_item_option_value) {
            MenuItemOptionValue::create([
                'id' => $menu_item_option_value['id'],
                'menu_item_option_id' => $menu_item_option_value['menu_item_option_id'],
                'cost' => $menu_item_option_value['cost'],
                'order' => $menu_item_option_value['order'],
                'value' => $menu_item_option_value['value'],
            ]);
        }

        Seat::create([
            'store_id' => 1,
            'number' => 'A1',
            'order' => 1,
            'status' => 1,
            'qr_code' => 'test qr code',
        ]);

        Customer::create([
            'seat_id' => 1,
            'token' => 'test token',
            'start_at' => '2024-03-01 12:00:00',
            'end_at' => '2024-03-01 14:00:00',
        ]);

        Order::create([
            'customer_id' => 1,
            'menu_item_id' => 1,
            'quantity' => 1,
            'price' => 500,
            'status' => 0,
            'created_at' => '2024-03-01 12:00:00',
        ]);

        OrderOption::Create([
            'order_id' => 1,
            'menu_item_option_value_id' => 1,
        ]);

        Sale::create([
            'store_id' => 1,
            'order_id' => 1,
            'amount' => 500,
            'paid_at' => '2024-03-01 12:00:00',
        ]);
    }
}
