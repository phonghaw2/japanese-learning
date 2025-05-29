<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningSession extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vocabulary_id',
        'is_remembered'
    ];

    /**
     * Get the vocabulary word associated with this learning session.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vocabulary(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vocabulary::class);
    }

    /**
     * Check if the vocabulary was remembered in this session.
     *
     * @return bool
     */
    public function wasRemembered(): bool
    {
        return (bool) $this->is_remembered;
    }

    /**
     * Mark the session as remembered.
     *
     * @return void
     */
    public function markAsRemembered(): void
    {
        $this->is_remembered = true;
        $this->save();
    }

    /**
     * Mark the session as forgotten.
     *
     * @return void
     */
    public function markAsForgotten(): void
    {
        $this->is_remembered = false;
        $this->save();
    }
}
