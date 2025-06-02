<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Synonym extends Model
{
    use HasFactory;

    protected $fillable = [
        'synonym',
        'vocabulary_id',
    ];

    /**
     * Get the vocabulary word associated with this synonym.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vocabulary()
    {
        return $this->belongsTo(Vocabulary::class);
    }
}
