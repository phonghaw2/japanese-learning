<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('synonyms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vocabulary_id');
            $table->string('synonym');
            $table->timestamps();

            // Tạo khóa ngoại
            $table->foreign('vocabulary_id')
                  ->references('id')->on('vocabularies')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('synonyms');
    }
};
