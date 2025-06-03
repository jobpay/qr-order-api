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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seat_id')->constrained('seats')->onDelete('cascade')->comment('座席ID');
            $table->string('token')->comment('トークン');
            $table->tinyInteger('status')->default(true)->comment('座席セッション状態');
            $table->timestamp('start_at')->nullable()->comment('利用開始時間');
            $table->timestamp('end_at')->nullable()->comment('利用終了時間');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
