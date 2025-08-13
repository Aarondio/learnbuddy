<?php

namespace App\Livewire;

use App\Models\Note;
use App\Services\GeminiService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class NoteList extends Component
{
    use WithPagination;

    protected $listeners = ['note-uploaded' => '$refresh'];

    public $search = '';

    #[Layout('layouts.app')]
    public function render()
    {
        $notes = Note::where('user_id', auth()->id())
            ->when($this->search, function ($q) {
                $q->where('title', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(8);

        return view('livewire.note-list', [
            'notes' => $notes,
        ]);
    }

    public function summarize(int $noteId): void
    {
        $note = Note::where('user_id', auth()->id())->findOrFail($noteId);
        if ($note->summary) {
            return;
        }
        $gemini  = app(GeminiService::class);
        $summary = $gemini->summarize($note->text_content);
        $note->update(['summary' => $summary]);
        $this->resetPage();
    }
}
