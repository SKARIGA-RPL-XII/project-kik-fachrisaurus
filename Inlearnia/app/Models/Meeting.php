<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'type',
        'title',
        'link',
        'deadline',
        'topic',
        'description',
    ];

    protected $casts = [
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
