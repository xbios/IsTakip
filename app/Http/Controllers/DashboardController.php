<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'pending_tasks' => \App\Models\Task::where('status', 'pending')
                ->when(!$user->isAdmin(), fn($q) => $q->where('assigned_user_id', $user->id)->orWhere('created_by', $user->id))
                ->count(),
            'in_progress_tasks' => \App\Models\Task::where('status', 'in_progress')
                ->when(!$user->isAdmin(), fn($q) => $q->where('assigned_user_id', $user->id)->orWhere('created_by', $user->id))
                ->count(),
            'completed_tasks' => \App\Models\Task::where('status', 'completed')
                ->when(!$user->isAdmin(), fn($q) => $q->where('assigned_user_id', $user->id)->orWhere('created_by', $user->id))
                ->count(),
            'total_documents' => \App\Models\Document::when(!$user->isAdmin(), fn($q) => $q->where('created_by', $user->id)->orWhere('related_type', 'global'))
                ->count(),
        ];

        $recent_tasks = \App\Models\Task::with('assignedUser')
            ->when(!$user->isAdmin(), fn($q) => $q->where('assigned_user_id', $user->id)->orWhere('created_by', $user->id))
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recent_tasks'));
    }
}
