<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Submission;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MeetingController extends Controller
{
    public function show(Meeting $meeting)
    {
        $meeting->load('classRoom');

        $submission = Submission::where('meeting_id', $meeting->id)
            ->where('student_id', auth()->id())
            ->first();

        return view('siswa.meetings.show', compact('meeting', 'submission'));
    }

    public function generateSummary(Meeting $meeting)
    {
        $meeting->load('classRoom');
        $student = auth()->user();

        // Ambil atau siapkan submission
        $submission = Submission::where('meeting_id', $meeting->id)
            ->where('student_id', $student->id)
            ->first();

        $forceRegenerate = request()->boolean('force');

        // Kalau sudah ada summary & tidak force, return cache
        if ($submission?->ai_summary && !$forceRegenerate) {
            return response()->json([
                'summary'      => $submission->ai_summary,
                'generated_at' => $submission->ai_summary_generated_at
                    ? \Carbon\Carbon::parse($submission->ai_summary_generated_at)->format('d M Y, H:i')
                    : '-',
                'cached' => true,
            ]);
        }

        // ── Kumpulkan konten dari meeting ──
        $contents = [];

        // 1. Judul & topik sebagai konteks
        $titleText = 'Judul Materi: ' . $meeting->title;
        if ($meeting->topic) {
            $titleText .= "\nTopik: " . $meeting->topic;
        }
        $contents[] = ['type' => 'text', 'data' => $titleText];

        // 2. Deskripsi (strip HTML)
        if ($meeting->description) {
            $contents[] = [
                'type' => 'text',
                'data' => strip_tags($meeting->description),
            ];
        }

        // 3. File lampiran
        $rawFiles = $meeting->files;
        if (is_string($rawFiles)) {
            $rawFiles = json_decode($rawFiles, true);
            if (is_string($rawFiles)) {
                $rawFiles = json_decode($rawFiles, true);
            }
        }
        $files = is_array($rawFiles) ? $rawFiles : [];

        foreach ($files as $file) {
            if (!isset($file['path'])) continue;

            $fullPath  = Storage::disk('public')->path($file['path']);
            if (!file_exists($fullPath)) continue;

            $extension = strtolower(pathinfo($file['path'], PATHINFO_EXTENSION));

            if ($extension === 'pdf') {
                $base64     = base64_encode(file_get_contents($fullPath));
                $contents[] = ['type' => 'pdf', 'data' => $base64];

            } elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                $mimeMap    = [
                    'jpg'  => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png'  => 'image/png',
                    'webp' => 'image/webp',
                ];
                $base64     = base64_encode(file_get_contents($fullPath));
                $contents[] = [
                    'type'      => 'image',
                    'data'      => $base64,
                    'mime_type' => $mimeMap[$extension],
                ];

            } elseif (in_array($extension, ['docx', 'doc'])) {
                // Ekstrak teks dari DOCX
                $text = $this->extractDocxText($fullPath);
                if ($text) {
                    $contents[] = ['type' => 'text', 'data' => $text];
                }
            }
        }

        if (count($contents) <= 1) {
            return response()->json([
                'error' => 'Tidak ada konten yang cukup untuk diringkas.'
            ], 422);
        }

        try {
            $gemini  = new GeminiService();
            $summary = $gemini->summarize($contents);

            // Simpan ke submission — buat jika belum ada
            if (!$submission) {
                $submission = Submission::create([
                    'meeting_id'  => $meeting->id,
                    'student_id'  => $student->id,
                    'submitted_at' => null, 
                ]);
            }

            $submission->update([
                'ai_summary'              => $summary,
                'ai_summary_generated_at' => now(),
            ]);

            return response()->json([
                'summary'      => $summary,
                'generated_at' => now()->format('d M Y, H:i'),
                'cached'       => false,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ── Helper: ekstrak teks dari DOCX ──
    private function extractDocxText(string $path): string
    {
        try {
            $phpWord  = \PhpOffice\PhpWord\IOFactory::load($path);
            $fullText = '';

            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $fullText .= $element->getText() . "\n";
                    } elseif (method_exists($element, 'getElements')) {
                        foreach ($element->getElements() as $child) {
                            if (method_exists($child, 'getText')) {
                                $fullText .= $child->getText() . ' ';
                            }
                        }
                        $fullText .= "\n";
                    }
                }
            }

            return trim($fullText);
        } catch (\Exception $e) {
            return '';
        }
    }
}