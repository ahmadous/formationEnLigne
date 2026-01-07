<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;
    protected $fillable=['title','description','video_url','video_path','course_id', 'status', 'block_reason'];
    
    public function course(){
        return $this->belongsTo(Course::class);
    }

    /**
     * Get all discussions for this episode.
     */
    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

    /**
     * Get all assignments for this episode.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get the reports for this episode.
     */
    public function reports()
    {
        return $this->hasMany(ReportedEpisode::class);
    }

    /**
     * Get users who completed this episode.
     */
    public function completedBy()
    {
        return $this->belongsToMany(User::class, 'completions');
    }
    
    /**
     * Get the video URL - returns uploaded video path or external URL.
     */
    public function getVideoUrlAttribute()
    {
        if ($this->video_path && \Storage::disk('public')->exists($this->video_path)) {
            return asset('storage/' . $this->video_path);
        }
        return $this->attributes['video_url'] ?? null;
    }
}
