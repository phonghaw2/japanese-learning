<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\ReadingType;

class Reading extends Model
{
    use HasFactory;

    protected $fillable = [
        'vocabulary_id',
        'reading',
        'type',
    ];

    protected $casts = [
        'type' => ReadingType::class,
    ];

    public function vocabulary()
    {
        return $this->belongsTo(Vocabulary::class);
    }

    public function examples()
    {
        return $this->hasMany(ReadingExample::class);
    }
}

