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
        Schema::create('menu_item_option_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_option_id')->constrained('menu_item_options')->onDelete('cascade')->comment('メニューオプションID');
            $table->integer('order')->comment('表示順');
            $table->string('value')->comment('オプション内容');
            $table->decimal('cost', 8, 2)->nullable()->comment('追加料金');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_item_option_values');
    }
};
