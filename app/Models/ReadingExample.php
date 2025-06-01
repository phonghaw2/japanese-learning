<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReadingExample extends Model
{
    use HasFactory;

    protected $fillable = [
        'reading_id',
        'word',
        'meaning',
        'pronunciation',
    ];

    public function reading()
    {
        return $this->belongsTo(Reading::class);
    }
}

