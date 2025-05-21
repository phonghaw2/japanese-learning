<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    use HasFactory;

    protected $fillable = [
        'word',
        'kanji',
        'meaning',
        'romaji',
        'part_of_speech',
        'jlpt_level',
        'appearance_count',
        'remembered_count'
    ];

    /**
     * Lấy các câu ví dụ liên quan đến từ vựng
     */
    public function exampleSentences()
    {
        return $this->hasMany(ExampleSentence::class);
    }

    /**
     * Lấy các phiên học liên quan đến từ vựng
     */
    public function learningSessions()
    {
        return $this->hasMany(LearningSession::class);
    }

    /**
     * Tăng số lần xuất hiện của từ này
     */
    public function incrementAppearance()
    {
        $this->appearance_count += 1;
        $this->save();
    }

    /**
     * Tăng số lần được đánh dấu là đã nhớ
     */
    public function incrementRemembered()
    {
        $this->remembered_count += 1;
        $this->save();
    }

    /**
     * Tính tỷ lệ ưu tiên cho flashcard (ưu tiên từ ít xuất hiện hoặc ít được nhớ)
     */
    public function getPriorityScore()
    {
        if ($this->appearance_count == 0) {
            return 100; // Từ mới chưa xuất hiện lần nào được ưu tiên cao nhất
        }

        $rememberRatio = $this->remembered_count / $this->appearance_count;
        return 100 * (1 - $rememberRatio); // Từ càng ít được nhớ, điểm càng cao
    }
}
