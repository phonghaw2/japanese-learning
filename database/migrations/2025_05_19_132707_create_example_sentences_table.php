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
        Schema::create('example_sentences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vocabulary_id');
            $table->text('japanese_sentence');  // Câu ví dụ tiếng Nhật
            $table->text('meaning');            // Nghĩa tiếng Việt
            $table->text('romaji')->nullable();  // Phiên âm Latin của câu
            $table->timestamps();

            $table->foreign('vocabulary_id')
                  ->references('id')
                  ->on('vocabularies')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('example_sentences');
    }
};
