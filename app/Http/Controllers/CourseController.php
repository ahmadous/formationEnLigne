<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Episode;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('user')->select('courses.*',DB::raw(
            '(SELECT COUNT(DISTINCT(user_id))
            FROM completions
            INNER JOIN episodes ON completions.episode_id = episode_id
            WHERE episodes.course_id = courses.id
            )AS participants'
        ))->withCount('episodes')->latest()->get();
        return Inertia::render('Courses/index', ['courses' => $courses]);
    }
    public function show(int $id)
    {
        $course = Course::where('id', $id)->with('episodes')->first();
        $vu=auth()->user()->episodes;
        return Inertia::render('Courses/show', [
            'course' => $course,
            'vu'=>$vu,
        ]);
    }
    public function toggleProgress(Request $request)
    {
        $id = $request->input('episodeId');
        $user = auth()->user();
        $user->episodes()->toggle($id);

        return $user->episodes;
    }

    public function edit(int $id){
        $course=Course::where('id',$id)->with('episodes')->first();
        $this->authorize('update',$course);
        return Inertia::render('Courses/edit', [
            'course' => $course,
        ]);
    }
    public function update(Request $request){
        $course=Course::where('id',$request->id)->with('episodes')->first();
        $this->authorize('update',$course);
        $course->update($request->all());
        $course->episodes()->delete();
        foreach($request->episodes as $episode){
            $episode['course_id']=$course->id;
            Episode::create($episode);
        }
        return to_route('courses.index')->with('message','Felicitation la formation a bien ete  modifier');
    }
    public function store(Request $request){
        $request->validate([
            'title'=>'required',
            'description'=>'required',
            'episodes'=>['required','array'],
            'episodes.*.title'=>'required',
            'episodes.*.description'=>'required',
            'episodes.*.video_url'=>'required'
        ]);
        $course=Course::create($request->all());
        foreach ($request->input('episodes') as $episode){
            $episode['course_id']=$course->id;
            Episode::create($episode);
        }
        return to_route('courses.index')->with('message','Felicitation la formation a bien ete  cree');
    }
}
