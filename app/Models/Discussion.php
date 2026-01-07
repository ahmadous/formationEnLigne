<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    use HasFactory;

    protected $fillable = [
        'episode_id',
        'course_id',
        'user_id',
        'title',
        'content',
        'is_pinned',
        'is_solved',
        'solved_by',
    ];

    /**
     * Get the episode this discussion belongs to.
     */
    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }

    /**
     * Get the course this discussion belongs to.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the user who created this discussion.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the instructor who solved this discussion.
     */
    public function solvedByUser()
    {
        return $this->belongsTo(User::class, 'solved_by');
    }

    /**
     * Get all replies to this discussion.
     */
    public function replies()
    {
        return $this->hasMany(DiscussionReply::class);
    }
}
