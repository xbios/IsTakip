<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description_md',
        'status',
        'priority',
        'start_date',
        'end_date',
        'assigned_user_id',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function steps()
    {
        return $this->hasMany(TaskStep::class)->orderBy('order');
    }

    public function logs()
    {
        return $this->hasMany(TaskLog::class)->latest();
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'related_id')->where('related_type', 'task');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
