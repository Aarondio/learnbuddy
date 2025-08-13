<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Books Library</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                @switch($filter)
                    @case('mine')
                        {{ $myBookCount }} {{ Str::plural('book', $myBookCount) }} uploaded by you
                        @break
                    @case('public')
                        {{ $publicBookCount }} public {{ Str::plural('book', $publicBookCount) }}
                        @break
                    @case('my_library')
                        {{ $myLibraryCount }} {{ Str::plural('book', $myLibraryCount) }} in your library
                        @break
                    @default
                        {{ $totalBooks }} {{ Str::plural('book', $totalBooks) }} available
                @endswitch
            </p>
        </div>
        @can('create', \App\Models\Book::class)
            <a href="{{ route('books.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Book
            </a>
        @endcan
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-8">
        <div class="flex flex-col space-y-4">
            <!-- Search and Filter Toggle -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text" id="search" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-3 py-2 sm:text-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md" placeholder="Search by title, author, or ISBN">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter</label>
                        <select wire:model.live="filter" id="filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="all">All Books</option>
                            <option value="public">Public Books</option>
                            @auth
                                <option value="mine">My Uploads</option>
                                <option value="my_library">My Library</option>
                            @endauth
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button @click="$wire.toggleFilters()" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="h-5 w-5 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Advanced Filters -->
            <div x-show="$wire.showFilters" x-transition class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Categories Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Publication Year</label>
                        <div class="space-y-2 max-h-40 overflow-y-auto p-2 border rounded-md">
                            @foreach($availableYears as $year)
                                <div class="flex items-center">
                                    <input id="year-{{ $year }}" type="checkbox" 
                                        wire:model.live="selectedYears" 
                                        value="{{ $year }}"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                                    <label for="year-{{ $year }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $year }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button wire:click="resetFilters" type="button" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        Reset all filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if($books->isEmpty())
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No books found</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                @if($search || $filter !== 'all')
                    Try adjusting your search or filter to find what you're looking for.
                @else
                    Get started by adding a new book.
                @endif
            </p>
            @can('create', \App\Models\Book::class)
                <div class="mt-6">
                    <a href="{{ route('books.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        New Book
                    </a>
                </div>
            @endcan
        </div>
    @else
        <!-- Sorting Controls -->
        <div class="mb-4 flex flex-col sm:flex-row justify-between items-center bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="text-sm text-gray-500 dark:text-gray-400 mb-2 sm:mb-0">
                Showing {{ $books->firstItem() ?? 0 }} to {{ $books->lastItem() ?? 0 }} of {{ $books->total() }} results
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Sort by:</span>
                <select wire:model.live="sortField" class="text-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="title">Title</option>
                    <option value="author">Author</option>
                    <option value="publication_year">Year</option>
                    <option value="created_at">Date Added</option>
                </select>
                <button wire:click="$set('sortDirection', '{{ $sortDirection === 'asc' ? 'desc' : 'asc' }}')" class="p-1 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                    @if($sortDirection === 'asc')
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    @else
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    @endif
                </button>
            </div>
        </div>

        <!-- Books Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($books as $book)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col h-full">
                    <div class="relative h-48 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        @if($book->cover_image)
                            <img class="h-full w-full object-cover" src="{{ $book->cover_image_url }}" alt="{{ $book->title }}">
                        @else
                            <div class="text-gray-400 dark:text-gray-500">
                                <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-2 right-2 flex space-x-1">
                            @if($book->is_public)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-200 dark:text-green-900">
                                    Public
                                </span>
                            @endif
                            @if($book->users_count > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-200 dark:text-blue-900">
                                    {{ $book->users_count }} {{ Str::plural('reader', $book->users_count) }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1 line-clamp-2" title="{{ $book->title }}">
                            {{ $book->title }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">By {{ $book->author }}</p>
                        
                        @if($book->publication_year)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                                {{ $book->publication_year }}
                                @if($book->publisher)
                                    • {{ $book->publisher }}
                                @endif
                            </p>
                        @endif
                        
                        <div class="mt-auto pt-3 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex justify-between items-center">
                                <a href="{{ route('books.show', $book) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    View Details
                                </a>
                                @auth
                                    @if(!$book->users->contains(auth()->id()))
                                        <button wire:click="$dispatch('add-to-library', { bookId: {{ $book->id }} }}" 
                                                class="text-sm font-medium text-green-600 hover:text-green-500 dark:text-green-400 dark:hover:text-green-300 flex items-center"
                                                wire:loading.attr="disabled">
                                            <span wire:loading.remove wire:target="$dispatch('add-to-library', { bookId: {{ $book->id }} }}">
                                                Add to Library
                                            </span>
                                            <span wire:loading wire:target="$dispatch('add-to-library', { bookId: {{ $book->id }} })">
                                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </span>
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $books->links() }}
        </div>
    @endif

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                @this.on('book-added', (event) => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            });
        </script>
    @endpush
</div>{{-- If your happiness depends on money, you will never be happy with yourself. --}}
</div>
