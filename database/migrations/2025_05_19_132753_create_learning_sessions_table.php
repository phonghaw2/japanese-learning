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
        Schema::create('learning_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vocabulary_id');
            $table->boolean('is_remembered')->default(false);  // Đã nhớ hay chưa
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
        Schema::dropIfExists('learning_sessions');
    }
};
