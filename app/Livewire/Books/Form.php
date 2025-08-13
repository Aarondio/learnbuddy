<?php

namespace App\Livewire\Books;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;

class Form extends Component
{
    use WithFileUploads;

    public Book $book;
    public $bookFile;
    public $coverImage;
    public $isUploading = false;
    public $isEdit = false;
    public $confirmingBookDeletion = false;

    protected function rules()
    {
        return [
            'book.title' => ['required', 'string', 'max:255'],
            'book.author' => ['required', 'string', 'max:255'],
            'book.description' => ['nullable', 'string'],
            'book.isbn' => ['nullable', 'string', 'max:20'],
            'book.publisher' => ['nullable', 'string', 'max:255'],
            'book.publication_year' => ['nullable', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'book.is_public' => ['boolean'],
            'bookFile' => [
                Rule::requiredIf(!$this->isEdit),
                'nullable',
                'file',
                'mimes:pdf',
                'max:102400' // 100MB
            ],
            'coverImage' => [
                'nullable',
                'image',
                'max:5120', // 5MB
                'mimes:jpeg,png,jpg,gif,webp'
            ],
        ];
    }

    protected $messages = [
        'bookFile.required' => 'Please upload a PDF file.',
        'bookFile.mimes' => 'The book must be a PDF file.',
        'bookFile.max' => 'The book must not be larger than 100MB.',
        'coverImage.image' => 'The cover must be an image.',
        'coverImage.mimes' => 'The cover must be a file of type: jpeg, png, jpg, gif, or webp.',
        'coverImage.max' => 'The cover must not be larger than 5MB.',
    ];

    public function mount(Book $book = null)
    {
        $this->isEdit = $book->exists;
        $this->book = $book ?? new Book();
        
        if (!$this->isEdit) {
            $this->book->is_public = true;
        } else {
            $this->authorize('update', $this->book);
        }
    }

    public function save()
    {
        $this->validate();
        $this->isUploading = true;

        try {
            // Handle file upload for new books
            if ($this->bookFile) {
                $filename = 'books/' . Str::random(40) . '.pdf';
                $this->book->file_path = $this->bookFile->storeAs('public', $filename);
            }

            // Handle cover image upload
            if ($this->coverImage) {
                $coverPath = $this->coverImage->store('public/covers');
                $this->book->cover_image = str_replace('public/', '', $coverPath);
            }

            if ($this->isEdit) {
                $this->book->update();
                $message = 'Book updated successfully!';
            } else {
                $this->book->uploaded_by = auth()->id();
                $this->book->save();
                $message = 'Book uploaded successfully!';
            }

            return redirect()->route('books.show', $this->book)
                ->with('success', $message);

        } catch (\Exception $e) {
            $this->addError('upload', 'An error occurred while processing your request. Please try again.');
            \Log::error('Book save error: ' . $e->getMessage());
            $this->isUploading = false;
        }
    }

    public function removeCover()
    {
        if ($this->book->cover_image) {
            Storage::delete('public/' . $this->book->cover_image);
            $this->book->cover_image = null;
            $this->book->save();
            $this->coverImage = null;
            
            session()->flash('success', 'Cover image removed successfully.');
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Cover image removed successfully.'
            ]);
        }
    }

    public function delete()
    {
        $this->authorize('delete', $this->book);
        
        // Delete associated files
        if ($this->book->file_path) {
            Storage::disk('public')->delete($this->book->file_path);
        }
        if ($this->book->cover_image) {
            Storage::disk('public')->delete($this->book->cover_image);
        }
        
        $this->book->delete();
        
        $this->dispatch('book-deleted', url: route('books.index'));
    }

    public function render()
    {
        return view('livewire.books.form', [
            'isEdit' => $this->isEdit,
            'title' => $this->isEdit ? 'Edit Book' : 'Upload New Book'
        ]);
    }
}
