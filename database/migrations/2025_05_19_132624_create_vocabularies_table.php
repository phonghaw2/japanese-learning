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
        Schema::create('vocabularies', function (Blueprint $table) {
            $table->id();
            $table->string('word');             // Từ vựng tiếng Nhật (hiragana/katakana)
            $table->string('kanji')->nullable(); // Hán tự (nếu có)
            $table->text('meaning');            // Ý nghĩa (tiếng Việt)
            $table->text('romaji')->nullable();  // Phiên âm Latin
            $table->string('part_of_speech')->nullable(); // Loại từ (danh từ, động từ, etc.)
            $table->string('jlpt_level')->nullable();
            $table->integer('appearance_count')->default(0); // Số lần xuất hiện trong flashcard
            $table->integer('remembered_count')->default(0); // Số lần được đánh dấu là đã nhớ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocabularies');
    }
};
