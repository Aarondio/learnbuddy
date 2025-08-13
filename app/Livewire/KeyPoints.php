<?php

namespace App\Livewire;

use App\Models\KeyPoint;
use App\Models\Note;
use Livewire\Component;

class KeyPoints extends Component
{
    public Note $note;
    public string $newKeyPoint = '';
    public ?int $editingKeyPointId = null;
    public string $editingKeyPointContent = '';

    protected $listeners = ['refreshKeyPoints' => '$refresh'];

    public function mount(Note $note): void
    {
        $this->note = $note->load('keyPoints');
    }

    public function addKeyPoint(): void
    {
        $this->validate([
            'newKeyPoint' => ['required', 'string', 'max:255'],
        ]);

        $this->note->keyPoints()->create([
            'content' => $this->newKeyPoint,
            'order' => $this->note->keyPoints()->count() + 1
        ]);

        $this->newKeyPoint = '';
        $this->note->refresh();
        $this->dispatch('notify', 'Key point added!');
    }

    public function editKeyPoint(KeyPoint $keyPoint): void
    {
        $this->editingKeyPointId = $keyPoint->id;
        $this->editingKeyPointContent = $keyPoint->content;
    }

    public function updateKeyPoint(KeyPoint $keyPoint): void
    {
        $this->validate([
            'editingKeyPointContent' => ['required', 'string', 'max:255'],
        ]);

        $keyPoint->update([
            'content' => $this->editingKeyPointContent
        ]);

        $this->cancelEdit();
        $this->note->refresh();
        $this->dispatch('notify', 'Key point updated!');
    }

    public function cancelEdit(): void
    {
        $this->editingKeyPointId = null;
        $this->editingKeyPointContent = '';
    }

    public function deleteKeyPoint(KeyPoint $keyPoint): void
    {
        $keyPoint->delete();
        $this->note->refresh();
        $this->dispatch('notify', 'Key point deleted!');
    }

    public function render()
    {
        return view('livewire.key-points');
    }
}
