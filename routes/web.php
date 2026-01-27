<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('tasks', \App\Http\Controllers\TaskController::class);
    Route::post('tasks/{task}/steps', [\App\Http\Controllers\TaskStepController::class, 'store'])->name('tasks.steps.store');
    Route::patch('steps/{taskStep}', [\App\Http\Controllers\TaskStepController::class, 'update'])->name('steps.update');
    Route::delete('steps/{taskStep}', [\App\Http\Controllers\TaskStepController::class, 'destroy'])->name('steps.destroy');

    Route::resource('documents', \App\Http\Controllers\DocumentController::class);

    Route::post('/markdown-preview', function (\Illuminate\Http\Request $request) {
        return response()->json([
            'html' => \App\Services\MarkdownService::parse($request->input('content'))
        ]);
    })->name('markdown.preview');

    Route::post('/media/upload', [\App\Http\Controllers\MediaController::class, 'upload'])->name('media.upload');

    Route::get('/documents/{document}/content', function (\App\Models\Document $document) {
        return response()->json([
            'title' => $document->title,
            'html' => \App\Services\MarkdownService::parse($document->content_md)
        ]);
    })->name('documents.content');
});

require __DIR__ . '/auth.php';
