<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskStepController extends Controller
{
    public function store(Request $request, \App\Models\Task $task)
    {
        Gate::authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $task->steps()->create([
            'title' => $validated['title'],
            'order' => $task->steps()->count() + 1,
        ]);

        return redirect()->back()->with('success', 'Step added.');
    }

    public function update(Request $request, \App\Models\TaskStep $taskStep)
    {
        Gate::authorize('update', $taskStep->task);

        $taskStep->update([
            'is_completed' => $request->has('is_completed'),
        ]);

        return redirect()->back()->with('success', 'Step updated.');
    }

    public function destroy(\App\Models\TaskStep $taskStep)
    {
        Gate::authorize('update', $taskStep->task);

        $taskStep->delete();

        return redirect()->back()->with('success', 'Step deleted.');
    }
}
