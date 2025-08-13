<?php

namespace App\Livewire\Books;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Book;
use App\Models\UserBook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'all'; // all, mine, public, my_library
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 12;
    public $showFilters = false;
    public $advancedFilters = [
        'title' => '',
        'author' => '',
        'publisher' => '',
        'year_from' => '',
        'year_to' => '',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'filter' => ['except' => 'all'],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 12],
    ];

    protected $listeners = ['bookAddedToLibrary', 'bookRemovedFromLibrary', 'refreshBooks' => '$refresh'];

    public function bookAddedToLibrary($bookId)
    {
        $this->resetPage();
    }

    public function bookRemovedFromLibrary($bookId)
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filter', 'advancedFilters']);
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function render()
    {
        $query = Book::query()
            ->with('uploader')
            ->withCount('users as readers_count')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('author', 'like', '%' . $this->search . '%')
                      ->orWhere('publisher', 'like', '%' . $this->search . '%')
                      ->orWhere('isbn', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filter === 'mine', function ($query) {
                $query->where('uploaded_by', auth()->id());
            })
            ->when($this->filter === 'public', function ($query) {
                $query->where('is_public', true);
            })
            ->when($this->filter === 'my_library', function ($query) {
                $query->whereHas('users', function($q) {
                    $q->where('user_id', auth()->id());
                });
            });

        // Apply advanced filters
        $query->when(!empty($this->advancedFilters['title']), function ($query) {
            $query->where('title', 'like', '%' . $this->advancedFilters['title'] . '%');
        })->when(!empty($this->advancedFilters['author']), function ($query) {
            $query->where('author', 'like', '%' . $this->advancedFilters['author'] . '%');
        })->when(!empty($this->advancedFilters['publisher']), function ($query) {
            $query->where('publisher', 'like', '%' . $this->advancedFilters['publisher'] . '%');
        })->when(!empty($this->advancedFilters['year_from']), function ($query) {
            $query->where('publication_year', '>=', $this->advancedFilters['year_from']);
        })->when(!empty($this->advancedFilters['year_to']), function ($query) {
            $query->where('publication_year', '<=', $this->advancedFilters['year_to']);
        });

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $books = $query->paginate($this->perPage);

        // Add is_in_library flag to each book
        $books->getCollection()->each(function($book) {
            $book->is_in_library = $book->isInUserLibrary(auth()->user());
        });

        // Get available years for filter
        $availableYears = Book::select(DB::raw('DISTINCT publication_year as year'))
            ->whereNotNull('publication_year')
            ->orderBy('publication_year', 'desc')
            ->pluck('year')
            ->filter()
            ->toArray();

        return view('livewire.books.index', [
            'books' => $books,
            'availableYears' => $availableYears,
            'totalBooks' => Book::count(),
            'myBookCount' => Book::where('uploaded_by', Auth::id())->count(),
            'publicBookCount' => Book::where('is_public', true)->count(),
            'myLibraryCount' => UserBook::where('user_id', Auth::id())->count(),
        ]);
    }
}
