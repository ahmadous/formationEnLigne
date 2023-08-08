<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class Course extends Model
{
    use HasFactory;

    protected $fillable=['title','description'];

    protected $appends=['permission'];

    protected static function booted(){
        static::creating(function($course){
            $course->user_id=auth()->user()->id;
        });
    }

    public function getPermissionAttribute(){
        return $this->creating('update-course',$this);
    }

    public function episodes(){
        return $this->hasMany(Episode::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
