<?php

namespace App\Http\Controllers;

use App\Models\Category;
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
        ))->withCount('episodes')->with('category')->latest()->get();
        return Inertia::render('Courses/index', ['courses' => $courses]);
    }
    
    public function myCourses()
    {
        $courses = Course::where('user_id', auth()->id())
            ->withCount('episodes')
            ->with('category')
            ->latest()
            ->get()
            ->map(function ($course) {
                $course->participants = DB::table('completions')
                    ->join('episodes', 'completions.episode_id', '=', 'episodes.id')
                    ->where('episodes.course_id', $course->id)
                    ->distinct('completions.user_id')
                    ->count('completions.user_id');
                return $course;
            });
            
        return Inertia::render('Courses/my-courses', ['courses' => $courses]);
    }
    
    public function create()
    {
        $this->authorize('create', Course::class);
        $categories = Category::all();
        return Inertia::render('Courses/create', ['categories' => $categories]);
    }
    
    public function show(int $id)
    {
        $course = Course::where('id', $id)->with('episodes')->first();
        $vu = auth()->check() ? auth()->user()->episodes : collect([]);
        return Inertia::render('Courses/show', [
            'course' => $course,
            'vu'=>$vu,
        ]);
    }
    
    public function createEpisode(int $id)
    {
        $course = Course::where('id', $id)->firstOrFail();
        $this->authorize('update', $course);
        
        return Inertia::render('Episodes/create', ['course' => $course]);
    }
    
    public function storeEpisode(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'video_url' => 'required',
            'course_id' => 'required|exists:courses,id',
        ]);
        
        $course = Course::where('id', $request->course_id)->firstOrFail();
        $this->authorize('update', $course);
        
        Episode::create($request->all());
        
        return to_route('courses.show', $course->id)->with('message', 'Épisode ajouté avec succès');
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
        $this->authorize('create', Course::class);
        $request->validate([
            'title'=>'required',
            'description'=>'required',
            'category_id'=>'nullable|exists:categories,id',
            'episodes'=>['required','array'],
            'episodes.*.title'=>'required',
            'episodes.*.description'=>'required',
            'episodes.*.video_url'=>'required'
        ]);
        
        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
        ]);
        
        foreach ($request->input('episodes') as $episodeData){
            Episode::create([
                'title' => $episodeData['title'],
                'description' => $episodeData['description'],
                'video_url' => $episodeData['video_url'],
                'video_path' => $episodeData['video_path'] ?? null,
                'course_id' => $course->id,
            ]);
        }
        
        return to_route('courses.my')->with('message','Félicitations la formation a bien été créée');
    }

    /**
     * Quick store for minimal course creation (used from dashboard quick form).
     */
    public function storeQuick(Request $request)
    {
        $this->authorize('create', Course::class);
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description ?? '',
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
        ]);
        
        return response()->json([
            'success' => true,
            'course' => $course,
            'edit_url' => route('courses.edit', $course->id),
        ], 201);
    }

    /**
     * Delete an episode.
     */
    public function deleteEpisode(int $id)
    {
        $episode = Episode::findOrFail($id);
        $course = $episode->course;
        
        $this->authorize('delete', $episode);
        
        $episode->delete();
        
        return to_route('courses.edit', $course->id)->with('message', 'Épisode supprimé avec succès');
    }

    /**
     * Toggle course publication status.
     */
    public function togglePublish(Request $request, int $id)
    {
        $course = Course::findOrFail($id);
        $this->authorize('update', $course);
        
        $course->update(['is_published' => !$course->is_published]);
        
        return to_route('courses.my')->with(
            'message',
            $course->is_published ? 'Formation publiée avec succès' : 'Formation masquée avec succès'
        );
    }

    /**
     * Pause or resume an episode (instructor or admin).
     */
    public function updateEpisodeStatus(Request $request, int $id)
    {
        $episode = Episode::findOrFail($id);
        $this->authorize('manageStatus', $episode);
        
        $request->validate([
            'status' => 'required|in:active,paused,blocked',
            'reason' => 'required_if:status,blocked',
        ]);
        
        $episode->update([
            'status' => $request->status,
            'block_reason' => $request->reason ?? null,
        ]);
        
        return to_route('courses.edit', $episode->course_id)->with('message', 'Statut de l\'épisode mis à jour');
    }
}

