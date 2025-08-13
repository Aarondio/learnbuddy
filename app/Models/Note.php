<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Note extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'user_id',
    //     'title',
    //     'pdf_path',
    //     'text_content',
    //     'summary',
    // ];

    protected $guarded = [];

    public function keyPoints(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(KeyPoint::class)->orderBy('order');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
