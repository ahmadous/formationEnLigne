<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'total_episodes',
        'completed_episodes',
        'total_assignments',
        'submitted_assignments',
        'graded_assignments',
        'average_grade',
        'completion_percentage',
        'status',
        'enrolled_at',
        'completed_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
