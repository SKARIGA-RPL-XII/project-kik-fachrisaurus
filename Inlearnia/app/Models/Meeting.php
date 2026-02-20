<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meeting extends Model
{





    use HasFactory;

    // Pastikan semua nama kolom sama persis dengan yang ada di migrasi
    protected $fillable = [
        'class_id',
        'type',
        'title',
        'description',
        'topic',
        'links',
        'files',
        'deadline',
        'disable_late_submission',
        'max_score',
    ];

    protected $casts = [
        'links' => 'array',
        'files' => 'array',
        'disable_late_submission' => 'boolean',
        'deadline' => 'datetime',
    ];

    /* ================= RELATIONSHIPS ================= */

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function discussions()
    {
        return $this->hasMany(DiscussionPost::class);
    }
}