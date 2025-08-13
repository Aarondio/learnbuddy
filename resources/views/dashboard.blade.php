@extends('layouts.app')

@section('content')
    <div class="min-h-screen px-4">
        <!-- Top nav -->
        <header class="flex items-center justify-between py-4">
            <a href="{{ route('dashboard') }}" class="text-xl font-semibold flex items-center gap-2">
                <svg class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
                </svg>
                Student Buddy
            </a>
            <div class="flex items-center gap-3">
                <a href="#" class="hover:text-indigo-400">Settings</a>
                <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-sm font-semibold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        <!-- Upload form & notes list -->
        <div class="container mx-auto my-8">
            <h1 class="text-3xl font-bold">Welcome, {{ auth()->user()->name }}!</h1>
        </div>

        {{-- <livewire:note-uploader /> --}}

        <livewire:note-list />
    </div>
@endsection
