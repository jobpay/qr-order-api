<?php

namespace Tests\Feature\Shop\Category;

use App\Models\Menu\Category;
use App\Models\Store\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function 正常系_カテゴリーを削除できる(): void
    {
        // テストデータの準備
        $store = Store::factory()->create();
        $user = User::factory()->create([
            'store_id' => $store->id,
        ]);

        $category = Category::factory()->create([
            'store_id' => $store->id,
        ]);

        // API呼び出し
        $response = $this->actingAs($user)
            ->deleteJson("/api/categories/{$category->id}");

        // レスポンスのアサーション
        $response->assertStatus(200);

        // データベースのアサーション
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    /**
     * @test
     */
    public function 異常系_認証されていない場合はエラーとなる(): void
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/categories/{$category->id}");

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

        $response = $this->actingAs($user)
            ->deleteJson('/api/categories/999999');

        $response->assertStatus(400)
            ->assertJson([
                'errors' => ['指定されたカテゴリーが見つかりません。']
            ]);
    }

    /**
     * @test
     */
    public function 異常系_他店舗のカテゴリーは削除できない(): void
    {
        $store1 = Store::factory()->create();
        $store2 = Store::factory()->create();

        $user = User::factory()->create([
            'store_id' => $store1->id,
        ]);

        $category = Category::factory()->create([
            'store_id' => $store2->id,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(400)
            ->assertJson([
                'errors' => ['指定されたカテゴリーの削除権限がありません。']
            ]);
    }
}
