<div>
    <section class="container mx-auto mt-8">
        <div class="flex mb-8">
            <input type="text" wire:model.debounce.300ms="search" placeholder="Search notes..."
                class="flex-1 w-full bg-gray-700 text-white placeholder-gray-400 rounded-md border border-gray-600 py-2 pl-4 pr-24 focus:outline-none  focus:ring-indigo-500" />
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Create new note tile -->
            <a href="{{ route('notes.upload') }}"
                class="flex flex-col items-center justify-center border-2 border-dashed border-gray-600 rounded-lg h-34 bg-gray-800 hover:bg-gray-700 transition">
                <svg class="h-8 w-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span class="mt-2 text-sm">Create new note</span>
            </a>
            @forelse ($notes as $note)
                @if ($note->summary)
                    <a href="{{ route('notes.chat', $note) }}"
                        class="block p-4 bg-gray-800 rounded-lg shadow hover:bg-gray-700">
                        <h3 class="text-lg font-semibold">{{ $note->title }}</h3>
                        <p class="text-sm text-gray-300 mt-2">{{ \Illuminate\Support\Str::limit($note->summary, 112) }}
                        </p>
                    </a>
                @else
                    <div class="p-4 bg-gray-800 rounded-lg shadow">
                        <h3 class="text-lg font-semibold">{{ $note->title }}</h3>
                        <p class="text-sm text-gray-300 mt-2">No summary yet.</p>
                        <button wire:click="summarize({{ $note->id }})"
                            class="mt-3 inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            <span wire:loading wire:target="summarize({{ $note->id }})"
                                class="animate-pulse">Summarizing...</span>
                            <span wire:loading.remove wire:target="summarize({{ $note->id }})">Summarize</span>
                        </button>
                    </div>
                @endif
            @empty
                <p>No notes found.</p>
            @endforelse
        </div>

        <div class="mt-4">{{ $notes->links() }}</div>
    </section>
</div>
