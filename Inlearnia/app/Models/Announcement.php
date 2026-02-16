<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi balik ke Kelas
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }
}