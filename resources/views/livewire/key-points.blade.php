<div class="flex flex-col h-full">
    <div class="flex-1 overflow-y-auto pb-4 custom-scrollbar mb-[5rem] mt-4">
        <div class="space-y-4">
            <!-- Existing key points -->
            @if ($note->keyPoints && count($note->keyPoints) > 0)
                @foreach ($note->keyPoints as $point)
                    <div
                        class="group relative bg-gray-700/50 rounded-lg p-3 mx-4 text-sm text-gray-200 hover:bg-gray-700/70 transition-colors">
                        @if ($editingKeyPointId === $point->id)
                            <div class="flex flex-col space-y-2">
                                <textarea type="text" wire:model.defer="editingKeyPointContent" wire:keydown.enter="updateKeyPoint({{ $point->id }})"
                                    class="w-full bg-gray-600 border border-gray-500 rounded px-2 py-1 text-white focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                    autofocus></textarea>
                                <div class="flex justify-end space-x-2 mt-2">
                                    <button wire:click="cancelEdit"
                                        class="px-2 py-1 text-xs text-gray-300 hover:text-white">
                                        Cancel
                                    </button>
                                    <button wire:click="updateKeyPoint({{ $point->id }})"
                                        class="px-2 py-1 text-xs bg-indigo-600 hover:bg-indigo-500 text-white rounded">
                                        Save
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="flex justify-between items-start">
                                <span>{{ $point->content }}</span>
                                <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="editKeyPoint({{ $point->id }})"
                                        class="text-gray-400 hover:text-indigo-400" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="deleteKeyPoint({{ $point->id }})"
                                        class="text-gray-400 hover:text-red-400" title="Delete"
                                        onclick="return confirm('Are you sure you want to delete this key point?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <p class="text-sm text-gray-400 italic mx-4">No key points added yet.</p>
            @endif

        </div>
    </div>

    <!-- Fixed Add Key Point section at the bottom -->
    <div class=" border-t border-gray-700 fixed bottom-0 w-90 px-4  py-4 bg-[#1E2938] ">
        <div class="space-y-2">
            <!-- <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span class="text-sm font-medium text-gray-300">Add Key Point</span>
            </div> -->
            <div class="flex gap-2">
                <input type="text" wire:model.debounce.500ms="newKeyPoint" wire:keydown.enter="addKeyPoint"
                    placeholder="Add key point..."
                    class="flex-1 bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-sm text-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                <button wire:click="addKeyPoint"
                    class="px-3 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm rounded-md flex items-center justify-center disabled:opacity-50"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </span>
                    <span wire:loading>Adding...</span>
                </button>
            </div>
            @error('newKeyPoint')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('print-keypoints-content', (e) => {
                const win = window.open('', '', 'width=800,height=600');
                win.document.write(
                    `<pre style="font-family: Inter, sans-serif; white-space: pre-wrap;">${e.detail.content}</pre>`);
                win.document.close();
                win.focus();
                win.print();
            });
        </script>
    @endpush
</div>
