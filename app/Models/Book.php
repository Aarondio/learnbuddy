<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use App\Models\Note;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'author',
        'description',
        'isbn',
        'publisher',
        'publication_year',
        'cover_image',
        'file_path',
        'uploaded_by',
        'is_public',
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'is_public' => 'boolean',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('note_id', 'added_at')
            ->withTimestamps();
    }

    public function notes()
    {
        return $this->hasManyThrough(
            Note::class,
            'book_user',
            'book_id',
            'id',
            'id',
            'note_id'
        );
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function getCoverImageUrlAttribute()
    {
        return $this->cover_image 
            ? asset('storage/' . $this->cover_image)
            : asset('images/default-book-cover.jpg');
    }

    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    public function canBeAccessedBy(User $user): bool
    {
        return $this->is_public || 
               $this->uploaded_by === $user->id || 
               $user->isAdmin();
    }

    public function addToUserLibrary(User $user)
    {
        if (!$this->users()->where('user_id', $user->id)->exists()) {
            $note = Note::create([
                'user_id' => $user->id,
                'title' => $this->title . ' - Notes',
                'source' => 'book:' . $this->id,
            ]);
            
            $this->users()->attach($user->id, ['note_id' => $note->id]);
            return $note;
        }
        
        return null;
    }

    public function isInUserLibrary(User $user): bool
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }
}
