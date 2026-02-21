<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Casting agar field JSON otomatis menjadi Array di PHP
    protected $casts = [
        'links' => 'array',
        'files' => 'array',
    ];

    // Relasi balik ke Kelas
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }
    
    // Relasi ke Pembuat Pengumuman
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}