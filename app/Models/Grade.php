<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'graded_by',
        'score',
        'feedback',
        'rubric_feedback',
        'status',
        'graded_at',
    ];

    protected $casts = [
        'graded_at' => 'datetime',
    ];

    /**
     * Get the submission this grade is for.
     */
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    /**
     * Get the instructor who graded.
     */
    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
