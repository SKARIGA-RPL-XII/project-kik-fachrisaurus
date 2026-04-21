<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'meeting_id',
        'student_id',
        'content_text',
        'links',
        'files',
        'submitted_at',
        'score',      
        'feedback',   
        'ai_summary',
        'ai_summary_generated_at',
    ];

    public function getStatusAttribute(): string
    {
        if (!$this->submitted_at)
            return 'missing';
        if ($this->score !== null)
            return 'graded';

        // Cek terlambat — load meeting jika belum
        $deadline = $this->meeting->deadline;
        if ($deadline && $this->submitted_at->gt($deadline))
            return 'late';

        return 'submitted';
    }

    protected $casts = [
        'submitted_at' => 'datetime',
        'ai_summary_generated_at' => 'datetime',
        'links' => 'array',
        'files' => 'array',
    ];


    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}