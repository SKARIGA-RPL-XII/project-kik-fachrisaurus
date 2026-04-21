<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('meeting_id')->constrained('meetings')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();

            // Isi jawaban
            $table->text('content_text')->nullable();

            // Lampiran fleksibel (seperti announcements)
            $table->json('links')->nullable();
            $table->json('files')->nullable();

            // Waktu submit
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            // 1 siswa hanya 1 submission per meeting
            $table->unique(['meeting_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};