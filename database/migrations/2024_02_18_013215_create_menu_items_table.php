<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade')->comment('店舗ID');
            $table->unsignedInteger('category_id')->comment('カテゴリーID');
            $table->integer('number')->comment('メニュー番号');
            $table->string('name')->comment('メニュー名');
            $table->text('description')->nullable()->comment('説明');
            $table->decimal('price', 8, 2)->comment('価格');
            $table->string('image')->nullable()->comment('メニュー画像');
            $table->tinyInteger('status')->default(0)->comment('ステータス');
            $table->timestamps();

            // store_idとcategory_idとnumberでユニーク
            $table->unique(['store_id', 'category_id', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
