<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
     * Get the example sentences related to this vocabulary word.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exampleSentences(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ExampleSentence::class);
    }

    /**
     * Get the learning sessions associated with this vocabulary word.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function learningSessions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LearningSession::class);
    }

    /**
     * Get the synonyms associated with this vocabulary word.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function synonyms()
    {
        return $this->hasMany(Synonym::class);
    }

    /**
     * Increment the appearance count of this word.
     *
     * @return void
     */
    public function incrementAppearance(): void
    {
        $this->increment('appearance_count');
    }

    /**
     * Increment the remembered count of this word.
     *
     * @return void
     */
    public function incrementRemembered(): void
    {
        $this->increment('remembered_count');
    }

    /**
     * Calculate the priority score for flashcard appearance.
     * A higher score means the word is less familiar and should be shown more often.
     *
     * @return float
     */
    public function getPriorityScore(): float
    {
        if ($this->appearance_count === 0) {
            return 100.0; // Highest priority for new words
        }

        $rememberRatio = $this->remembered_count / max(1, $this->appearance_count);
        return round(100 * (1 - $rememberRatio), 2);
    }

    /**
     * Mark this word as remembered (alias for incrementRemembered).
     *
     * @return void
     */
    public function markAsRemembered(): void
    {
        $this->incrementRemembered();
    }

    /**
     * Reset appearance and remembered counters (useful for testing or re-learning).
     *
     * @return void
     */
    public function resetCounters(): void
    {
        $this->appearance_count = 0;
        $this->remembered_count = 0;
        $this->save();
    }
}
