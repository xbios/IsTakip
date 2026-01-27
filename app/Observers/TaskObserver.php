<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        $task->logs()->create([
            'user_id' => auth()->id() ?? $task->created_by,
            'action' => 'created',
            'description' => __('Task created: :title', ['title' => $task->title]),
        ]);
    }

    public function updated(Task $task): void
    {
        if ($task->isDirty('status')) {
            $task->logs()->create([
                'user_id' => auth()->id(),
                'action' => 'status_updated',
                'description' => __('Status changed from :old to :new', [
                    'old' => __(ucfirst(str_replace('_', ' ', $task->getOriginal('status')))),
                    'new' => __(ucfirst(str_replace('_', ' ', $task->status)))
                ]),
            ]);
        }

        if ($task->isDirty('description_md')) {
            $task->logs()->create([
                'user_id' => auth()->id(),
                'action' => 'document_updated',
                'description' => __('Markdown description updated.'),
            ]);
        }
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
