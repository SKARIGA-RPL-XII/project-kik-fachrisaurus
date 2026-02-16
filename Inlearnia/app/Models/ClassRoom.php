<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassRoom extends Model
{
    use HasFactory;

    // Nama tabel manual
    protected $table = 'classes';

    protected $fillable = [
        'school_id',
        'subject_id',
        'teacher_id',
        'name',
        'description',
        'logo',
    ];

    /* ================= RELATIONSHIPS ================= */

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->belongsToMany(
            User::class,
            'class_students',
            'class_id',
            'student_id'
        );
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class, 'class_id');
    }

    // --- INI YANG BARU DITAMBAHKAN ---
    public function announcements()
    {
        // Parameter kedua 'class_id' wajib ada karena nama tabelmu 'classes'
        return $this->hasMany(Announcement::class, 'class_id');
    }
}