<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExampleSentence extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vocabulary_id',
        'japanese_sentence',
        'meaning',
        'romaji'
    ];

    /**
     * Get the vocabulary word associated with this example sentence.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vocabulary(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vocabulary::class);
    }

    /**
     * Get a formatted version of the sentence for display.
     *
     * @return string
     */
    public function formatted(): string
    {
        return "{$this->japanese_sentence} ({$this->romaji}) - {$this->meaning}";
    }

    /**
     * Check if the sentence contains a specific keyword.
     *
     * @param string $keyword
     * @return bool
     */
    public function contains(string $keyword): bool
    {
        return str_contains($this->japanese_sentence, $keyword)
            || str_contains($this->romaji, $keyword)
            || str_contains($this->meaning, $keyword);
    }
}
