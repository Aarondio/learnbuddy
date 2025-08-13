<?php

namespace App\Livewire;

use App\Models\Note;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Smalot\PdfParser\Parser as PdfParser;

#[Layout('layouts.app')]
class NoteUploader extends Component
{
    use WithFileUploads;

    public $pdf;
    public $title;
    public $progress = 0;
    // public function mount()
    // {
    //     $this->pdf = null;
    //     $this->title = 'Hello world';
    //     $this->progress = 0;
    // }

    public function render()
    {
        return view('livewire.note-uploader');
    }

    public function uploadpdf()
    {

    
        $this->validate([
            'pdf' => 'required|file|mimes:pdf|max:10240',  // 10 MB
            'title' => 'required|string|max:255',
        ]);


        // Store PDF
        $path = $this->pdf->store('notes', 'public');

        // Extract text from PDF
        $text = '';
        try {
            $parser  = new PdfParser();
            $pdfObj  = $parser->parseFile(Storage::disk('public')->path($path));
            $text    = $pdfObj->getText();
        } catch (\Throwable $e) {
            \Log::error('PDF parse failed', [
                'error' => $e->getMessage(),
            ]);
        }

        \Log::info('NoteUploader – ready to insert', [
            'user' => auth()->id(),
            'title' => $this->title,
        ]);

        Note::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'pdf_path' => $path,
            'text_content' => $text,
            'summary' => null,
        ]);

        $this->reset(['pdf', 'title']);
        $this->dispatch('note-uploaded');
        session()->flash('success', 'Note uploaded & summarized!');
    }
}
