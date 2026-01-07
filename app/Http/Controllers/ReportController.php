<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\ReportedEpisode;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Store a report for an episode.
     */
    public function reportEpisode(Request $request, int $episodeId)
    {
        $episode = Episode::findOrFail($episodeId);
        
        // Only students can report (not the instructor who created it)
        if ($episode->course->user_id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas signaler votre propre contenu'
            ], 403);
        }

        $request->validate([
            'reason' => 'required|string|in:inappropriate_content,copyright,offensive,other',
            'description' => 'required|string|min:10|max:500',
        ]);

        // Check if user already reported this episode
        $existing = ReportedEpisode::where('episode_id', $episodeId)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'under_review'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà signalé cet épisode'
            ], 409);
        }

        $report = ReportedEpisode::create([
            'episode_id' => $episodeId,
            'user_id' => auth()->id(),
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Merci, votre signalement a été enregistré. Notre équipe l\'examinera sous peu.',
            'report' => $report
        ]);
    }

    /**
     * Admin: View pending reports.
     */
    public function index()
    {
        $this->authorize('manage-reports');

        $reports = ReportedEpisode::with(['episode', 'user', 'episode.course'])
            ->whereIn('status', ['pending', 'under_review'])
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Reports', [
            'reports' => $reports
        ]);
    }

    /**
     * Admin: Update report status and take action.
     */
    public function updateReport(Request $request, int $reportId)
    {
        $this->authorize('manage-reports');

        $report = ReportedEpisode::findOrFail($reportId);
        $episode = $report->episode;

        $request->validate([
            'status' => 'required|in:under_review,resolved,dismissed',
            'action' => 'required_if:status,resolved|in:block,pause,warning,none',
            'admin_notes' => 'required|string|min:5',
        ]);

        // Update report
        $report->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // Take action on episode if resolved
        if ($request->status === 'resolved') {
            switch ($request->action) {
                case 'block':
                    $episode->update([
                        'status' => 'blocked',
                        'block_reason' => $request->admin_notes
                    ]);
                    break;
                case 'pause':
                    $episode->update([
                        'status' => 'paused',
                        'block_reason' => $request->admin_notes
                    ]);
                    break;
            }
        }

        return to_route('admin.reports')->with('message', 'Signalement mis à jour');
    }

    /**
     * Admin: Get all reports with filters.
     */
    public function allReports(Request $request)
    {
        $this->authorize('manage-reports');

        $query = ReportedEpisode::with(['episode', 'user', 'episode.course', 'reviewer']);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by reason
        if ($request->reason) {
            $query->where('reason', $request->reason);
        }

        $reports = $query->latest()->paginate(20);

        return Inertia::render('Admin/AllReports', [
            'reports' => $reports,
            'filters' => [
                'status' => $request->status,
                'reason' => $request->reason,
            ]
        ]);
    }
}
