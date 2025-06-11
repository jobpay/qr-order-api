<?php

namespace Tests\Feature\Shop\Category;

use App\Models\Menu\Category;
use App\Models\Store\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function 正常系_カテゴリ一覧を取得できる(): void
    {
        // テストデータの準備
        $store = Store::factory()->create();
        $user = User::factory()->create([
            'store_id' => $store->id,
        ]);

        $categories = Category::factory()->count(3)->create([
            'store_id' => $store->id,
        ]);

        // API呼び出し
        $response = $this->actingAs($user)
            ->getJson('/api/categories?' . http_build_query([
                'limit' => 10,
                'offset' => 0,
            ]));

        // レスポンスのアサーション
        $response->assertStatus(200)
            ->assertJsonStructure([
                'categories' => [
                    '*' => [
                        'id',
                        'name',
                        'order',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'total',
            ]);

        // データの検証
        $response->assertJson([
            'total' => 3,
            'categories' => $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'order' => $category->order,
                    'created_at' => $category->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $category->updated_at->format('Y-m-d H:i:s'),
                ];
            })->toArray(),
        ]);
    }

    /**
     * @test
     */
    public function 異常系_認証されていない場合はエラーとなる(): void
    {
        $response = $this->getJson('/api/categories?' . http_build_query([
            'limit' => 10,
            'offset' => 0,
        ]));

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function 異常系_他店舗のカテゴリは取得できない(): void
    {
        // テストデータの準備
        $store1 = Store::factory()->create();
        $store2 = Store::factory()->create();

        $user = User::factory()->create([
            'store_id' => $store1->id,
        ]);

        Category::factory()->count(3)->create([
            'store_id' => $store1->id,
        ]);

        Category::factory()->count(2)->create([
            'store_id' => $store2->id,
        ]);

        // API呼び出し
        $response = $this->actingAs($user)
            ->getJson('/api/categories?' . http_build_query([
                'limit' => 10,
                'offset' => 0,
            ]));

        // レスポンスのアサーション
        $response->assertStatus(200)
            ->assertJson([
                'total' => 3,
            ]);
    }
}
