<?php

namespace App\Services;

use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;



class GeminiService
{
    public function summarize(string $text): string
    {
        $response = Prism::text()
            ->using(Provider::Gemini, 'gemini-1.5-flash')
            ->withSystemPrompt('You are a smart AI assistant for students. Summarize notes and answer questions based on the PDF content.')
            ->withPrompt("Summarize the following note:\n{$text}")
            ->asText();

        return $response->text ?? '';
    }

    public function answer(string $noteText, string $question): string
    {
        $response = Prism::text()
            ->using(Provider::Gemini, 'gemini-1.5-flash')
            ->withSystemPrompt("You are a smart tutor. Use this student's note to answer any question based on the provided note.")
            ->withPrompt("Note:\n{$noteText}\n\nQuestion: {$question}")
            ->asText();

        return $response->text ?? '';
    }
}
