<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();

            $table->enum('type', ['materi', 'tugas']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('topic')->nullable();
            
            // Perubahan: Gunakan tipe JSON untuk array links dan files
            $table->json('links')->nullable();
            $table->json('files')->nullable();
            
            // Kolom khusus tugas
            $table->dateTime('deadline')->nullable();
            $table->boolean('disable_late_submission')->default(false);
            $table->integer('max_score')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};