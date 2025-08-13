<div>

    <section id="uploadForm" class="container mx-auto p-6 bg-gray-800 rounded-lg shadow text-white">
        <form wire:submit="uploadpdf" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" wire:model="title"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="e.g. Chapter 1 – Biology" />
                @error('title')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">PDF File</label>
                <input type="file" wire:model="pdf"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                    accept="application/pdf" />
                @error('pdf')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div wire:loading wire:target="pdf, upload" class="text-indigo-600">Uploading & summarizing...</div>

            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Upload</button>
        </form>

        @if (session()->has('success'))
            <div class="mt-4 text-green-600">{{ session('success') }}</div>
        @endif
    </section>
</div>
