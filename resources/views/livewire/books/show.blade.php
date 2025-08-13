<div class="py-8">
    {{-- Care about people's approval and you will be their prisoner. --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back button -->
        <div class="mb-6">
            <a href="{{ route('books.index') }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Books
            </a>
        </div>

        <!-- Book Details -->
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                        {{ $book->title }}
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                        by {{ $book->author }}
                        @if($book->publication_year)
                            &middot; {{ $book->publication_year }}
                        @endif
                    </p>
                </div>
                <div class="flex space-x-2">
                    @if($canEdit)
                        <a href="{{ route('books.edit', $book) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-900 dark:text-indigo-200 dark:hover:bg-indigo-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                    @endif
                    @if($canDelete)
                        <button wire:click="$dispatch('open-modal', 'confirm-book-deletion')" type="button" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-red-900 dark:text-red-200 dark:hover:bg-red-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    @endif
                </div>
            </div>
            <div class="border-t border-gray-200 dark:border-gray-700">
                <dl>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Cover Image</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" class="h-48 rounded shadow">
                        </dd>
                    </div>
                    @if($book->isbn)
                        <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">ISBN</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                {{ $book->isbn }}
                            </dd>
                        </div>
                    @endif
                    @if($book->publisher)
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Publisher</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                {{ $book->publisher }}
                            </dd>
                        </div>
                    @endif
                    <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Status</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                            @if($book->is_public)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Public
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Private
                                </span>
                            @endif
                        </dd>
                    </div>
                    @if($book->description)
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2 whitespace-pre-line">
                                {{ $book->description }}
                            </dd>
                        </div>
                    @endif
                    <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:px-6">
                        <div class="flex justify-end space-x-3">
                            @if(!$inLibrary)
                                <button wire:click="addToLibrary" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 01-1 1h-3a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Add to My Library
                                </button>
                            @else
                                <button wire:click="goToNote" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385V4.804z" />
                                        <path d="M9 4.804A7.968 7.968 0 0112 4c1.255 0 2.443.29 3.5.804v10A7.968 7.968 0 0012 14c-1.669 0-3.218.51-4.5 1.385V4.804z" />
                                    </svg>
                                    Go to Notes
                                </button>
                                <a href="{{ $book->file_url }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Download PDF
                                </a>
                            @endif
                        </div>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Delete Book Confirmation Modal -->
    <x-confirmation-modal wire:model="confirmingBookDeletion">
        <x-slot name="title">
            Delete Book
        </x-slot>

        <x-slot name="content">
            Are you sure you want to delete this book? This action cannot be undone.
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingBookDeletion')" wire:loading.attr="disabled">
                Cancel
            </x-secondary-button>

            <x-danger-button class="ml-2" wire:click="deleteBook" wire:loading.attr="disabled">
                Delete Book
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('book-deleted', () => {
                    window.location.href = '{{ route('books.index') }}';
                });
            });
        </script>
    @endpush
</div>
