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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('店舗名');
            $table->unsignedInteger('category_id')->nullable()->comment('店舗カテゴリー');
            $table->text('description')->nullable()->comment('店舗説明');
            $table->string('logo')->nullable()->comment('ロゴ');
            $table->string('postal_code')->nullable()->comment('郵便番号');
            $table->string('address')->nullable()->comment('住所');
            $table->timestamps();
            $table->softDeletes(); // 論理削除
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
