<div class="py-8">
    {{-- Success is as dangerous as failure. --}}
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    {{ $title }}
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Fill in the details below to {{ $isEdit ? 'update' : 'add' }} a book to the library.
                </p>
            </div>

            <form wire:submit.prevent="save" class="px-4 py-5 sm:px-6">
                @if (session()->has('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           wire:model="book.title" 
                           id="title"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"
                           required>
                    @error('book.title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Author -->
                <div class="mb-6">
                    <label for="author" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Author <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           wire:model="book.author" 
                           id="author"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"
                           required>
                    @error('book.author') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Book File Upload (Only for new books) -->
                @if(!$isEdit)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Book File (PDF) <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="bookFile" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="bookFile" name="bookFile" type="file" class="sr-only" wire:model="bookFile">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    PDF up to 100MB
                                </p>
                            </div>
                        </div>
                        @error('bookFile') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        @if($bookFile)
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                Selected: {{ $bookFile->getClientOriginalName() }}
                            </p>
                        @endif
                    </div>
                @endif

                <!-- Cover Image Upload -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Cover Image
                    </label>
                    @if($book->cover_image_url || $coverImage)
                        <div class="mt-1 flex items-center">
                            <img src="{{ $coverImage ? $coverImage->temporaryUrl() : $book->cover_image_url }}" 
                                 alt="Book cover" 
                                 class="h-32 w-auto object-cover rounded">
                            <div class="ml-4">
                                <button type="button" 
                                        wire:click="$set('coverImage', null)" 
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-red-900 dark:text-red-200 dark:hover:bg-red-800">
                                    Change
                                </button>
                                @if($book->cover_image)
                                    <button type="button" 
                                            wire:click="removeCover"
                                            class="ml-2 inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                        Remove
                                    </button>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="coverImage" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload a cover</span>
                                        <input id="coverImage" name="coverImage" type="file" class="sr-only" wire:model="coverImage" accept="image/*">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    PNG, JPG, GIF up to 5MB
                                </p>
                            </div>
                        </div>
                    @endif
                    @error('coverImage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Additional Book Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- ISBN -->
                    <div>
                        <label for="isbn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            ISBN
                        </label>
                        <input type="text" 
                               wire:model="book.isbn" 
                               id="isbn"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                        @error('book.isbn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Publisher -->
                    <div>
                        <label for="publisher" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Publisher
                        </label>
                        <input type="text" 
                               wire:model="book.publisher" 
                               id="publisher"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                        @error('book.publisher') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Publication Year -->
                    <div>
                        <label for="publication_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Publication Year
                        </label>
                        <input type="number" 
                               wire:model="book.publication_year" 
                               id="publication_year"
                               min="1000" 
                               max="{{ date('Y') + 1 }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                        @error('book.publication_year') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Visibility -->
                    <div class="flex items-center">
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <input id="is_public" 
                                       type="checkbox" 
                                       wire:model="book.is_public"
                                       class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_public" class="font-medium text-gray-700 dark:text-gray-300">Make this book public</label>
                                <p class="text-gray-500 dark:text-gray-400">Visible to all users</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Description
                    </label>
                    <textarea id="description" 
                              wire:model="book.description" 
                              rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"></textarea>
                    @error('book.description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Form Actions -->
                <div class="pt-5 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between">
                        @if($isEdit && $book->exists)
                            <div>
                                <button type="button"
                                        wire:click="$dispatch('open-modal', 'confirm-book-deletion')"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Delete Book
                                </button>
                            </div>
                        @else
                            <div></div>
                        @endif
                        
                        <div class="flex space-x-3">
                            <a href="{{ $isEdit && $book->exists ? route('books.show', $book) : route('books.index') }}" 
                               class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                                    wire:loading.attr="disabled"
                                    wire:target="save">
                                <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ $isEdit ? 'Update Book' : 'Upload Book' }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('modals')
        @if($isEdit && $book->exists)
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

                    <x-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                        Delete Book
                    </x-danger-button>
                </x-slot>
            </x-confirmation-modal>
        @endif
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                // Handle file upload preview
                Livewire.on('cover-updated', () => {
                    const fileInput = document.getElementById('bookFile');
                    const coverInput = document.getElementById('coverImage');
                    if (fileInput) fileInput.value = '';
                    if (coverInput) coverInput.value = '';
                });

                // Handle book deletion
                Livewire.on('book-deleted', (url) => {
                    window.location.href = url;
                });
            });
        </script>
    @endpush
</div>
