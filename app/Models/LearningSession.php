<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'vocabulary_id',
        'is_remembered'
    ];

    /**
     * Lấy từ vựng liên quan đến phiên học này
     */
    public function vocabulary()
    {
        return $this->belongsTo(Vocabulary::class);
    }
}
