<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;
    protected $fillable=['title','description','video_url','video_path','course_id'];
    
    public function course(){
        return $this->belongsTo(Course::class);
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
