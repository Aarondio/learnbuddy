<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeyPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'note_id',
        'content',
        'order'
    ];

    protected $casts = [
        'order' => 'integer',
    ];
    
    protected static function booted()
    {
        static::creating(function ($keyPoint) {
            if (empty($keyPoint->order)) {
                $keyPoint->order = static::where('note_id', $keyPoint->note_id)->max('order') + 1;
            }
        });
    }

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }
}
