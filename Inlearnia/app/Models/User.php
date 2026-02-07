<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\School;
use App\Models\ClassRoom;
use App\Models\Submission;
use App\Models\Grade;
use App\Models\DiscussionPost;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'school_id',
        'name',
        'email',
        'password',
        'role',
        'phone',
        'profile_photo',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* ================= RELATIONSHIPS ================= */

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /* sebagai pengajar */
    public function teachingClasses()
    {
        return $this->hasMany(ClassRoom::class, 'teacher_id');
    }

    /* sebagai siswa */
    public function classes()
    {
        return $this->belongsToMany(
            ClassRoom::class,
            'class_students',
            'student_id',
            'class_id'
        );
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'student_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id');
    }

    public function discussionPosts()
    {
        return $this->hasMany(DiscussionPost::class);
    }

}
