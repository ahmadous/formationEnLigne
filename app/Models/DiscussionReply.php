<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscussionReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'discussion_id',
        'user_id',
        'content',
        'is_instructor_answer',
    ];

    /**
     * Get the discussion this reply belongs to.
     */
    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    /**
     * Get the user who posted this reply.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
