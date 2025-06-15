<?php

namespace Tests\Feature\Shop\Category;

use App\Models\Menu\Category;
use App\Models\Store\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function 正常系_カテゴリーを新規作成できる(): void
    {
        // テストデータの準備
        $store = Store::factory()->create();
        $user = User::factory()->create([
            'store_id' => $store->id,
        ]);

        $params = [
            'name' => 'テストカテゴリー',
            'order' => 1,
        ];

        // API呼び出し
        $response = $this->actingAs($user)
            ->postJson('/api/categories', $params);

        // レスポンスのアサーション
        $response->assertStatus(200);

        // データベースのアサーション
        $this->assertDatabaseHas('categories', [
            'store_id' => $store->id,
            'name' => 'テストカテゴリー',
            'order' => 1,
        ]);
    }

    /**
     * @test
     */
    public function 異常系_認証されていない場合はエラーとなる(): void
    {
        $params = [
            'name' => 'テストカテゴリー',
            'order' => 1,
        ];

        $response = $this->postJson('/api/categories', $params);

        $response->assertStatus(401);
    }

    /**
     * @test
     * @dataProvider dataProvider_バリデーションエラー
     */
    public function 異常系_バリデーションエラー(array $params, string $expectedError): void
    {
        $store = Store::factory()->create();
        $user = User::factory()->create([
            'store_id' => $store->id,
        ]);

        $response = $this->actingAs($user)
            ->postJson('/api/categories', $params);

        $response->assertStatus(400)
            ->assertJson([
                'errors' => [$expectedError]
            ]);
    }

    /**
     * dataProvider_バリデーションエラー
     */
    public function dataProvider_バリデーションエラー(): array
    {
        return [
            'nameが未入力' => [
                'params' => ['order' => 1],
                'expectedError' => 'カテゴリー名は必須です'
            ],
            'orderが未入力' => [
                'params' => ['name' => 'テストカテゴリー'],
                'expectedError' => '表示順は必須です'
            ],
            'nameが101文字以上' => [
                'params' => [
                    'name' => str_repeat('あ', 101),
                    'order' => 1
                ],
                'expectedError' => 'カテゴリー名は100文字以内で入力してください'
            ],
            'nameが空文字' => [
                'params' => [
                    'name' => '',
                    'order' => 1
                ],
                'expectedError' => 'カテゴリー名は必須です'
            ],
            'orderが0以下' => [
                'params' => [
                    'name' => 'テストカテゴリー',
                    'order' => 0
                ],
                'expectedError' => '表示順は1以上で入力してください'
            ],
            'orderが小数' => [
                'params' => [
                    'name' => 'テストカテゴリー',
                    'order' => 1.5
                ],
                'expectedError' => '表示順は整数で入力してください'
            ]
        ];
    }

    /**
     * @test
     */
    public function 異常系_同じ店舗内で同じ表示順のカテゴリーは作成できない(): void
    {
        $store = Store::factory()->create();
        $user = User::factory()->create([
            'store_id' => $store->id,
        ]);

        // 既存のカテゴリーを作成
        Category::factory()->create([
            'store_id' => $store->id,
            'order' => 1,
        ]);

        // 同じ表示順で新しいカテゴリーを作成しようとする
        $response = $this->actingAs($user)
            ->postJson('/api/categories', [
                'name' => 'テストカテゴリー',
                'order' => 1,
            ]);

        $response->assertStatus(400)
            ->assertJson([
                'errors' => ['指定された表示順は既に使用されています。']
            ]);
    }
}
