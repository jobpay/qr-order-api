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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade')->comment('カスタマーID');
            $table->foreignId('menu_item_id')->constrained('menu_items')->onDelete('cascade')->comment('メニュー項目ID');
            $table->integer('quantity')->comment('数量');
            $table->decimal('price', 8, 2)->comment('合計価格');
            $table->tinyInteger('status')->default(0)->comment('注文状態');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
