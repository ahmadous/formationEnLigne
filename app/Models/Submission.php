<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'user_id',
        'content',
        'file_path',
        'submitted_at',
        'late_submitted_at',
        'is_late',
        'days_late',
        'status',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'late_submitted_at' => 'datetime',
    ];

    /**
     * Get the assignment this submission is for.
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Get the student who submitted.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the grade for this submission.
     */
    public function grade()
    {
        return $this->hasOne(Grade::class);
    }
}
