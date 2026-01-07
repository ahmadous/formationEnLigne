<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\DiscussionReply;
use App\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscussionController extends Controller
{
    /**
     * Get discussions for an episode
     */
    public function index(Episode $episode)
    {
        $discussions = Discussion::where('episode_id', $episode->id)
            ->with(['user', 'replies.user'])
            ->latest()
            ->get();

        return response()->json($discussions);
    }

    /**
     * Create a new discussion
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'episode_id' => 'required|exists:episodes,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:2000',
        ]);

        $discussion = Discussion::create([
            'episode_id' => $validated['episode_id'],
            'user_id' => Auth::id(),
            'course_id' => Episode::find($validated['episode_id'])->course_id,
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        return response()->json($discussion->load('user'), 201);
    }

    /**
     * Update a discussion
     */
    public function update(Request $request, Discussion $discussion)
    {
        $this->authorize('update', $discussion);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string|max:2000',
        ]);

        $discussion->update($validated);

        return response()->json($discussion);
    }

    /**
     * Delete a discussion
     */
    public function destroy(Discussion $discussion)
    {
        $this->authorize('delete', $discussion);

        $discussion->delete();

        return response()->json(['message' => 'Discussion deleted successfully']);
    }

    /**
     * Toggle discussion as solved
     */
    public function toggleSolved(Discussion $discussion)
    {
        $course = $discussion->course;
        
        // Only course author can mark as solved
        if (Auth::id() !== $course->user_id && !Auth::user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $discussion->update(['is_solved' => !$discussion->is_solved]);

        return response()->json($discussion);
    }

    /**
     * Add a reply to a discussion
     */
    public function reply(Request $request, Discussion $discussion)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $reply = DiscussionReply::create([
            'discussion_id' => $discussion->id,
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'is_instructor_answer' => Auth::user()->hasRole('instructor') || Auth::user()->hasRole('admin'),
        ]);

        return response()->json($reply->load('user'), 201);
    }

    /**
     * Toggle reply as helpful
     */
    public function toggleHelpful(DiscussionReply $reply)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $reply->helpful_votes += 1;
        $reply->save();

        return response()->json(['helpful_votes' => $reply->helpful_votes]);
    }

    /**
     * Mark reply as instructor answer
     */
    public function markAsAnswer(Request $request, DiscussionReply $reply)
    {
        $discussion = $reply->discussion;
        $course = $discussion->course;

        if (Auth::id() !== $course->user_id && !Auth::user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Unmark all other replies
        $discussion->replies()->update(['is_instructor_answer' => false]);

        // Mark this one
        $reply->update(['is_instructor_answer' => true]);
        $discussion->update(['solved_by_user_id' => $reply->user_id]);

        return response()->json($reply);
    }
}
