<?php

namespace Tests\Feature\Shop\Category;

use App\Models\Menu\Category;
use App\Models\Store\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function 正常系_カテゴリーを更新できる(): void
    {
        // テストデータの準備
        $store = Store::factory()->create();
        $user = User::factory()->create([
            'store_id' => $store->id,
        ]);

        $category = Category::factory()->create([
            'store_id' => $store->id,
            'name' => '元のカテゴリー名',
            'order' => 1,
        ]);

        $params = [
            'name' => '更新後のカテゴリー名',
            'order' => 2,
        ];

        // API呼び出し
        $response = $this->actingAs($user)
            ->putJson("/api/categories/{$category->id}", $params);

        // レスポンスのアサーション
        $response->assertStatus(200);

        // データベースのアサーション
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'store_id' => $store->id,
            'name' => '更新後のカテゴリー名',
            'order' => 2,
        ]);
    }

    /**
     * @test
     */
    public function 異常系_認証されていない場合はエラーとなる(): void
    {
        $category = Category::factory()->create();

        $params = [
            'name' => '更新後のカテゴリー名',
            'order' => 2,
        ];

        $response = $this->putJson("/api/categories/{$category->id}", $params);

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function 異常系_存在しないカテゴリーの場合はエラーとなる(): void
    {
        $store = Store::factory()->create();
        $user = User::factory()->create([
            'store_id' => $store->id,
        ]);

        $params = [
            'name' => '更新後のカテゴリー名',
            'order' => 2,
        ];

        $response = $this->actingAs($user)
            ->putJson('/api/categories/999999', $params);

        $response->assertStatus(400)
            ->assertJson([
                'errors' => ['指定されたカテゴリーが見つかりません。']
            ]);
    }

    /**
     * @test
     */
    public function 異常系_他店舗のカテゴリーは更新できない(): void
    {
        $store1 = Store::factory()->create();
        $store2 = Store::factory()->create();

        $user = User::factory()->create([
            'store_id' => $store1->id,
        ]);

        $category = Category::factory()->create([
            'store_id' => $store2->id,
        ]);

        $params = [
            'name' => '更新後のカテゴリー名',
            'order' => 2,
        ];

        $response = $this->actingAs($user)
            ->putJson("/api/categories/{$category->id}", $params);

        $response->assertStatus(400)
            ->assertJson([
                'errors' => ['指定されたカテゴリーの更新権限がありません。']
            ]);
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

        $category = Category::factory()->create([
            'store_id' => $store->id,
        ]);

        // nameが未入力
        $response = $this->actingAs($user)
            ->putJson("/api/categories/{$category->id}", [
                'order' => 1,
            ]);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['name']);

        // orderが未入力
        $response = $this->actingAs($user)
            ->putJson("/api/categories/{$category->id}", [
                'name' => 'テストカテゴリー',
            ]);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['order']);
    }

    /**
     * @test
     */
    public function 異常系_同じ店舗内で同じ表示順のカテゴリーは更新できない(): void
    {
        $store = Store::factory()->create();
        $user = User::factory()->create([
            'store_id' => $store->id,
        ]);

        // 既存のカテゴリーを作成
        Category::factory()->create([
            'store_id' => $store->id,
            'order' => 2,
        ]);

        $category = Category::factory()->create([
            'store_id' => $store->id,
            'order' => 1,
        ]);

        // 既に使用されている表示順に更新しようとする
        $response = $this->actingAs($user)
            ->putJson("/api/categories/{$category->id}", [
                'name' => 'テストカテゴリー',
                'order' => 2,
            ]);

        $response->assertStatus(400)
            ->assertJson([
                'errors' => ['指定された表示順は既に使用されています。']
            ]);
    }
}
