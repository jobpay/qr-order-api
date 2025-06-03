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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade')->comment('店舗ID');
            $table->string('number')->comment('座席番号');
            $table->unsignedInteger('order')->default(0)->comment('並び順');
            $table->tinyInteger('status')->default(0)->comment('座席の状態'); // 例: 0: 空き, 1: 使用中
            $table->text('qr_code')->comment('QRコード情報'); // Base64エンコードした画像データ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
