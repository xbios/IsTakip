<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->authorizeResource(\App\Models\Document::class, 'document');
    }

    public function index()
    {
        $query = \App\Models\Document::with('creator');

        if (!auth()->user()->isAdmin()) {
            $query->where('created_by', auth()->id())
                ->orWhere('related_type', 'global');
        }

        $documents = $query->latest()->paginate(10);

        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        $tasks = \App\Models\Task::all();
        return view('documents.create', compact('tasks'));
    }

    public function store(\App\Http\Requests\StoreDocumentRequest $request)
    {
        $validated = $request->validated();
        $validated['created_by'] = auth()->id();
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']) . '-' . uniqid();

        \App\Models\Document::create($validated);

        return redirect()->route('documents.index')->with('success', 'Document created successfully.');
    }

    public function show(\App\Models\Document $document)
    {
        $content_html = \App\Services\MarkdownService::parse($document->content_md);
        return view('documents.show', compact('document', 'content_html'));
    }

    public function edit(\App\Models\Document $document)
    {
        $tasks = \App\Models\Task::all();
        return view('documents.edit', compact('document', 'tasks'));
    }

    public function update(\App\Http\Requests\UpdateDocumentRequest $request, \App\Models\Document $document)
    {
        $validated = $request->validated();
        if ($document->title !== $validated['title']) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']) . '-' . uniqid();
        }

        $document->update($validated);

        return redirect()->route('documents.show', $document)->with('success', 'Document updated successfully.');
    }

    public function destroy(\App\Models\Document $document)
    {
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }
}
