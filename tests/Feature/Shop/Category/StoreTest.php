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
     */
    public function 異常系_バリデーションエラー_必須項目が未入力の場合(): void
    {
        $store = Store::factory()->create();
        $user = User::factory()->create([
            'store_id' => $store->id,
        ]);

        // nameが未入力
        $response = $this->actingAs($user)
            ->postJson('/api/categories', [
                'order' => 1,
            ]);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['name']);

        // orderが未入力
        $response = $this->actingAs($user)
            ->postJson('/api/categories', [
                'name' => 'テストカテゴリー',
            ]);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['order']);
    }

    /**
     * @test
     */
    public function 異常系_バリデーションエラー_文字数制限超過の場合(): void
    {
        $store = Store::factory()->create();
        $user = User::factory()->create([
            'store_id' => $store->id,
        ]);

        $response = $this->actingAs($user)
            ->postJson('/api/categories', [
                'name' => str_repeat('あ', 101), // 101文字
                'order' => 1,
            ]);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['name']);
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
