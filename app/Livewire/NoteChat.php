<?php

namespace App\Livewire;

use App\Models\Note;
use App\Models\NoteMessage;
use App\Services\GeminiService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class NoteChat extends Component
{
    public Note $note;
    public $question;
    public $title;

    protected $rules = [
        'title' => 'required|string|max:255',
    ];
    public $messages = [];
    public $thinking = false;

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.note-chat');
    }

    public function mount(Note $note)
    {
        $this->note = $note;
        $this->title = $note->title;
        $this->messages = NoteMessage::where('note_id', $note->id)
            ->orderBy('created_at')
            ->get(['role as role', 'content as text'])
            ->toArray();
    }

    public function updatedTitle($value)
    {
        $this->validate(['title' => 'required|string|max:255']);
        $this->note->update(['title' => $this->title]);
    }

    public function ask()
    {
        $this->validate([
            'question' => 'required|string|max:1000',
        ]);

        // Save user message
        NoteMessage::create([
            'note_id' => $this->note->id,
            'user_id' => auth()->id(),
            'role'    => 'user',
            'content' => $this->question,
        ]);

        $this->messages[] = ['role' => 'user', 'text' => $this->question];
        $this->thinking = true;

        // Call AI
        $gemini = app(GeminiService::class);
        $answer = $gemini->answer($this->note->text_content, $this->question);

        // Save AI message
        NoteMessage::create([
            'note_id' => $this->note->id,
            'role'    => 'ai',
            'content' => $answer,
        ]);

        $this->messages[] = ['role' => 'ai', 'text' => $answer];
        $this->dispatch('message-added');
        $this->thinking = false;
        $this->reset('question');
    }
}
