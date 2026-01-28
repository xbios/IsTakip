<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Http\Requests\StoreAttachmentRequest;
use App\Http\Requests\UpdateAttachmentRequest;

class AttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Attachment::with(['user', 'task']);

        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $attachments = $query->latest()->paginate(10);

        return view('attachments.index', compact('attachments'));
    }

    public function create()
    {
        $tasks = \App\Models\Task::all();
        if (!auth()->user()->isAdmin()) {
            $tasks = \App\Models\Task::where('created_by', auth()->id())
                ->orWhere('assigned_user_id', auth()->id())
                ->get();
        }
        return view('attachments.create', compact('tasks'));
    }

    public function store(StoreAttachmentRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('attachments', 'local'); // 'local' disk to keep them private

            Attachment::create([
                'user_id' => auth()->id(),
                'task_id' => $validated['task_id'],
                'title' => $validated['title'],
                'original_filename' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);

            return redirect()->route('attachments.index')->with('success', __('File uploaded successfully.'));
        }

        return back()->withErrors(['file' => __('File could not be uploaded.')]);
    }

    public function show(Attachment $attachment)
    {
        // Yetki kontrolü
        if (!auth()->user()->isAdmin() && $attachment->user_id !== auth()->id()) {
            abort(403);
        }

        $path = storage_path('app/private/' . $attachment->file_path);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => $attachment->file_type,
            'Content-Disposition' => 'inline; filename="' . $attachment->original_filename . '"'
        ]);
    }

    public function destroy(Attachment $attachment)
    {
        // Yetki kontrolü
        if (!auth()->user()->isAdmin() && $attachment->user_id !== auth()->id()) {
            abort(403);
        }

        \Illuminate\Support\Facades\Storage::disk('local')->delete($attachment->file_path);
        $attachment->delete();

        return redirect()->route('attachments.index')->with('success', __('File deleted successfully.'));
    }
}
