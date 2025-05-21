<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExampleSentence extends Model
{
    use HasFactory;

    protected $fillable = [
        'vocabulary_id',
        'japanese_sentence',
        'meaning',
        'romaji'
    ];

    /**
     * Lấy từ vựng liên quan đến câu ví dụ này
     */
    public function vocabulary()
    {
        return $this->belongsTo(Vocabulary::class);
    }
}
