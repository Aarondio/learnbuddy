<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::query()
            ->when(!auth()->user()->isAdmin(), function ($query) {
                return $query
                    ->where('is_public', true)
                    ->orWhere('uploaded_by', auth()->id());
            })
            ->latest()
            ->paginate(12);

        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Book::class);
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Book::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:20',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'bookFile' => 'required|file|mimes:pdf|max:102400',
            'coverImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_public' => 'boolean',
        ]);

        try {
            // Store the book file
            $filePath = $request->file('bookFile')->store('books', 'public');
            
            // Handle cover image
            $coverPath = null;
            if ($request->hasFile('coverImage')) {
                $coverPath = $request->file('coverImage')->store('covers', 'public');
            }

            // Create the book
            $book = Book::create([
                'title' => $validated['title'],
                'author' => $validated['author'],
                'description' => $validated['description'] ?? null,
                'isbn' => $validated['isbn'] ?? null,
                'publisher' => $validated['publisher'] ?? null,
                'publication_year' => $validated['publication_year'] ?? null,
                'file_path' => $filePath,
                'cover_image' => $coverPath,
                'uploaded_by' => auth()->id(),
                'is_public' => $validated['is_public'] ?? true,
            ]);

            return response()->json([
                'success' => true,
                'redirect' => route('books.show', $book)
            ]);

        } catch (\Exception $e) {
            \Log::error('Book upload failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload book. Please try again.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $this->authorize('view', $book);
        $userHasBook = auth()->check() && $book->users()->where('user_id', auth()->id())->exists();

        return view('books.show', compact('book', 'userHasBook'));
    }

    /**
     * Add book to user's library
     */
    public function addToLibrary(Book $book)
    {
        $this->authorize('view', $book);

        if (!$book->users()->where('user_id', auth()->id())->exists()) {
            // Create a new note for this book
            $note = Note::create([
                'user_id' => auth()->id(),
                'title' => $book->title . ' - Notes',
                'source' => 'book:' . $book->id,
            ]);

            // Attach book to user with the created note
            $book->users()->attach(auth()->id(), ['note_id' => $note->id]);

            return response()->json([
                'success' => true,
                'redirect' => route('notes.chat', $note),
                'message' => 'Book added to your library!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'This book is already in your library.'
        ], 422);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $this->authorize('update', $book);
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:20',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'coverImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_public' => 'boolean',
        ]);

        try {
            // Handle cover image update
            if ($request->hasFile('coverImage')) {
                // Delete old cover if exists
                if ($book->cover_image) {
                    Storage::disk('public')->delete($book->cover_image);
                }
                $validated['cover_image'] = $request->file('coverImage')->store('covers', 'public');
            }

            $book->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Book updated successfully!',
                'book' => $book->fresh()
            ]);

        } catch (\Exception $e) {
            \Log::error('Book update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update book. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);

        // Delete associated files
        if ($book->file_path) {
            Storage::disk('public')->delete($book->file_path);
        }
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', 'Book deleted successfully!');
    }
}
