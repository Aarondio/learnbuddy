<?php

namespace App\Livewire\Books;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public Book $book;
    public $inLibrary = false;
    public $noteId = null;

    public function mount(Book $book)
    {
        $this->book = $book;
        $this->checkIfInLibrary();
    }

    public function addToLibrary()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $note = $this->book->addToUserLibrary(Auth::user());

        if ($note) {
            $this->inLibrary = true;
            $this->noteId = $note->id;
            $this->dispatch('notify',
                type: 'success',
                message: 'Book added to your library!');
        } else {
            $this->dispatch('notify',
                type: 'info',
                message: 'This book is already in your library.');
        }
    }

    public function goToNote()
    {
        if ($this->noteId) {
            return redirect()->route('notes.chat', $this->noteId);
        }
    }

    protected function checkIfInLibrary()
    {
        if (Auth::check()) {
            $this->inLibrary = $this->book->isInUserLibrary(Auth::user());
            if ($this->inLibrary) {
                $this->noteId = $this
                    ->book
                    ->users()
                    ->where('user_id', Auth::id())
                    ->first()
                    ->pivot
                    ->note_id ?? null;
            }
        }
    }

    public function render()
    {
        return view('livewire.books.show', [
            'canEdit' => Auth::check() && (
                Auth::user()->isAdmin() ||
                $this->book->uploaded_by === Auth::id()
            ),
            'canDelete' => Auth::check() && (
                Auth::user()->isAdmin() ||
                $this->book->uploaded_by === Auth::id()
            ),
        ]);
    }
}
