<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task');
    }

    public function index()
    {
        $query = Task::with(['assignedUser', 'creator']);

        if (!auth()->user()->isAdmin()) {
            $query->where(function ($q) {
                $q->where('created_by', auth()->id())
                    ->orWhere('assigned_user_id', auth()->id());
            });
        }

        $tasks = $query->latest()->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $users = \App\Models\User::all();
        return view('tasks.create', compact('users'));
    }

    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        $validated['created_by'] = auth()->id();

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $task->load(['steps', 'logs.user', 'assignedUser', 'creator']);
        $description_html = \App\Services\MarkdownService::parse($task->description_md);

        return view('tasks.show', compact('task', 'description_html'));
    }

    public function edit(Task $task)
    {
        $users = \App\Models\User::all();
        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return redirect()->route('tasks.show', $task)->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
