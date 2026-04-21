<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
    }

    /**
     * Generate ringkasan dari konten meeting
     * $contents = array of ['type' => 'text'|'image'|'pdf', 'data' => ...]
     */
    public function summarize(array $contents): string
    {
        $parts = [];

        // Instruksi awal
        $parts[] = [
            'text' => 'Kamu adalah asisten belajar. Buatkan ringkasan materi berikut dalam Bahasa Indonesia yang jelas, terstruktur, dan mudah dipahami oleh siswa. Gunakan format: judul ringkasan, poin-poin utama, dan kesimpulan singkat.'
        ];

        foreach ($contents as $content) {
            if ($content['type'] === 'text' && !empty(trim($content['data']))) {
                $parts[] = ['text' => $content['data']];
            }

            if ($content['type'] === 'image') {
                $parts[] = [
                    'inline_data' => [
                        'mime_type' => $content['mime_type'],
                        'data' => $content['data'], // base64
                    ]
                ];
            }

            if ($content['type'] === 'pdf') {
                // Gemini 1.5 Flash bisa baca PDF sebagai inline_data
                $parts[] = [
                    'inline_data' => [
                        'mime_type' => 'application/pdf',
                        'data' => $content['data'], // base64
                    ]
                ];
            }
        }

        $response = Http::withQueryParameters(['key' => $this->apiKey])
            ->timeout(60)
            ->post($this->baseUrl, [
                'contents' => [
                    ['parts' => $parts]
                ],
                'generationConfig' => [
                    'temperature' => 0.3,
                    'maxOutputTokens' => 1024,
                ]
            ]);

        if ($response->failed()) {
            Log::error('Gemini API error', $response->json());
            throw new \Exception('Gagal menghubungi Gemini API: ' . $response->status());
        }

        return $response->json('candidates.0.content.parts.0.text')
            ?? 'Ringkasan tidak dapat dibuat saat ini.';
    }
}