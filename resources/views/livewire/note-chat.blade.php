<div x-data="{
    summaryOpen: false,
    keyPointsOpen: false,
    isMobile: window.innerWidth < 1024,
    init() {
        // Only open side panels by default on desktop
        if (!this.isMobile) {
            this.summaryOpen = true;
            this.keyPointsOpen = true;
        }
        // Update mobile state on resize
        window.addEventListener('resize', () => {
            this.isMobile = window.innerWidth < 1024;
        });
    },
    toggleSummary() {
        this.summaryOpen = !this.summaryOpen;
    },
    toggleKeyPoints() {
        this.keyPointsOpen = !this.keyPointsOpen;
    },
    get centerColSpan() {
        if (this.summaryOpen && this.keyPointsOpen) return 'lg:col-span-6';
        if (this.summaryOpen || this.keyPointsOpen) return 'lg:col-span-8';
        return 'lg:col-span-12';
    },
    get sideColSpan() {
        return 'lg:col-span-3';
    },
    closeSidebar() {
        if (this.isMobile) {
            this.summaryOpen = false;
            this.keyPointsOpen = false;
        }
    }
}" class="mx-auto grid grid-cols-1 lg:grid-cols-12 gap-0 min-h-screen bg-gray-900">

    <!-- Summary column -->
    <div x-show="summaryOpen" @click.away="isMobile && (summaryOpen = false)" :class="{
        'hidden lg:block': !summaryOpen,
        'col-span-3': summaryOpen,
        'fixed left-0 top-0 bottom-0 w-80 z-20': isMobile && summaryOpen,
        'lg:relative lg:left-auto lg:top-auto lg:bottom-auto lg:w-auto lg:z-0': !isMobile || !summaryOpen
    }"
        class="bg-gray-800 shadow overflow-y-auto h-screen transition-all duration-300 ease-in-out border-r border-gray-700 custom-scrollbar">
        <div class="sticky top-0 bg-gray-800 p-4 border-b border-gray-700 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white">Summary</h2>
            <button @click="toggleSummary" class="text-gray-400 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        <div class="p-4">
            <p class="text-sm whitespace-pre-wrap text-gray-300">{!! $note->summary ?? 'No summary yet.' !!}</p>
        </div>
    </div>

    <!-- Chat column -->
    <div :class="centerColSpan" class="bg-gray-900 p-4 flex flex-col h-screen relative" @click="closeSidebar">
        <!-- Collapse buttons for mobile -->
        <div class="flex lg:hidden mb-4 space-x-2">
            <button @click="summaryOpen = !summaryOpen"
                class="px-3 py-1 bg-gray-800 text-gray-300 rounded-md text-sm flex items-center border border-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                </svg>
                Summary
            </button>
            <button @click="keyPointsOpen = !keyPointsOpen"
                class="px-3 py-1 bg-gray-800 text-gray-300 rounded-md text-sm flex items-center border border-gray-700">
                Key Points
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- Collapse toggle buttons -->
        <div class="absolute top-1/2 transform -translate-y-1/2 left-0 -ml-4 z-10 flex flex-col space-y-2">
            <button @click="summaryOpen = !summaryOpen"
                class="w-8 h-8 bg-gray-800 hover:bg-gray-700 text-white rounded-full shadow-lg flex items-center justify-center border border-gray-700 transition-all duration-200"
                :class="{ 'opacity-0': summaryOpen }">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </button>
            <button @click="keyPointsOpen = !keyPointsOpen"
                class="w-8 h-8 bg-gray-800 hover:bg-gray-700 text-white rounded-full shadow-lg flex items-center justify-center border border-gray-700 transition-all duration-200"
                :class="{ 'opacity-0': keyPointsOpen }">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform rotate-180" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <div class="flex items-center justify-between mb-4 pr-4">
            <a href="{{ route('dashboard') }}"
                class="text-sm text-white hover:underline flex items-center gap-1 flex-none w-20">
                &larr; Back
            </a>
            <input type="text" wire:model.debounce.1000ms="title"
                class="text-2xl font-bold bg-transparent border-none focus:outline-none w-full text-right" />
        </div>

        <div class="flex-1 space-y-4 overflow-y-auto" id="chatScroll">
            <div class="flex flex-col gap-4">
                @foreach ($messages as $msg)
                    <div
                        class="p-3 rounded-lg  {{ $msg['role'] === 'user' ? 'ml-auto bg-gray-500 text-white  text-right ' : 'mr-auto bg-gray-700 max-w-xl' }}">
                        <p class="whitespace-pre-wrap text-sm">{{ $msg['text'] }}</p>
                    </div>
                @endforeach
            </div>
            @if ($thinking)
                <div class="mr-auto flex items-center gap-2 text-gray-300">
                    <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" fill="none" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                    </svg>
                    Thinking...
                </div>
            @endif
        </div>

        <form wire:submit.prevent="ask" class="mt-6 ">

            <div class="relative">
                <input type="text" wire:model.defer="question" placeholder="Ask anything about {{ $note->title }}"
                    class="w-full bg-gray-700 text-white placeholder-gray-400 rounded-xl border border-gray-600 py-4 pl-4 pr-24 focus:outline-none focus:ring-2 focus:ring-indigo-500" />


                <!-- send button -->
                <button type="submit"
                    class="absolute right-4 top-1/2 -translate-y-1/2 bg-white text-gray-900 p-2 rounded-full shadow flex items-center justify-center"
                    wire:target="ask" wire:loading.attr="disabled">
                    <svg wire:loading.remove wire:target="ask" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2 21l21-9L2 3v7l15 2-15 2v7z" />
                    </svg>
                    <svg wire:loading wire:target="ask" class="animate-spin h-5 w-5 text-gray-900" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" fill="none" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                    </svg>
                    <span class="sr-only">Send</span>
                </button>
            </div>
        </form>

        @error('question')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    @push('styles')
        <style>
            /* Custom thin scrollbar for chat area */

            #chatScroll,
            #summaryScroll {
                scrollbar-width: thin;
                scrollbar-color: #101828 transparent;
                /* thumb track */
            }

            #chatScroll::-webkit-scrollbar,
            #summaryScroll::-webkit-scrollbar {
                width: 4px;
                height: 16px;
            }

            #chatScroll::-webkit-scrollbar-track,
            #summaryScroll::-webkit-scrollbar-track {
                background: transparent;
            }

            #chatScroll::-webkit-scrollbar-thumb,
            #summaryScroll::-webkit-scrollbar-thumb {
                background-color: #4F46E5;
                /* indigo-600 */
                border-radius: 4px;
            }

            #chatScroll:hover::-webkit-scrollbar-thumb,
            #summaryScroll:hover::-webkit-scrollbar-thumb {
                background-color: #6366F1;
                /* indigo-500 lighter on hover */
            }
        </style>
    @endpush

    <!-- Key Points column -->
    <div x-show="keyPointsOpen" @click.away="isMobile && (keyPointsOpen = false)" :class="{
        'hidden lg:block': !keyPointsOpen,
        'col-span-3': keyPointsOpen,
        'fixed right-0 top-0 bottom-0 w-80 z-20': isMobile && keyPointsOpen,
        'lg:relative lg:right-auto lg:top-auto lg:bottom-auto lg:w-auto lg:z-0': !isMobile || !keyPointsOpen
    }"
        class="bg-gray-800 shadow overflow-y-auto h-screen transition-all duration-300 ease-in-out border-l border-gray-700 custom-scrollbar">
        <div class="sticky top-0 bg-gray-800 p-4 border-b border-gray-700 flex justify-between items-center z-50">
            <h2 class="text-xl font-semibold text-white">Key Points</h2>
            <button @click="toggleKeyPoints" class="text-gray-400 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L9.586 10 7.293 7.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        <div class="">
            <livewire:key-points :note="$note" />
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('message-added', () => {
            const el = document.getElementById('chatScroll');
            if (el) el.scrollTop = el.scrollHeight;
        });
    </script>
@endpush
