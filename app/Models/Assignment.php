<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'episode_id',
        'title',
        'description',
        'instructions',
        'due_date',
        'max_score',
        'submission_type',
        'allows_late_submission',
        'late_penalty_percent',
        'is_published',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    /**
     * Get the course this assignment belongs to.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the episode this assignment is for.
     */
    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }

    /**
     * Get all submissions for this assignment.
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
