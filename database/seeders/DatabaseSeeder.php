<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@user.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // Seed Tasks
        $task1 = \App\Models\Task::create([
            'title' => 'Project Kickoff',
            'description_md' => "# Kickoff Details\nThis is the initial phase.",
            'status' => 'completed',
            'priority' => 'high',
            'created_by' => $admin->id,
            'assigned_user_id' => $user->id,
            'start_date' => now(),
            'end_date' => now()->addDays(7),
        ]);

        $task1->steps()->createMany([
            ['title' => 'Setup repository', 'is_completed' => true, 'order' => 1],
            ['title' => 'Define requirements', 'is_completed' => true, 'order' => 2],
        ]);

        $task2 = \App\Models\Task::create([
            'title' => 'Implement Auth Module',
            'description_md' => 'Need to implement Breeze auth.',
            'status' => 'in_progress',
            'priority' => 'medium',
            'created_by' => $user->id,
            'assigned_user_id' => $user->id,
            'start_date' => now()->addDays(2),
        ]);

        // Seed Documents
        \App\Models\Document::create([
            'title' => 'System Architecture',
            'slug' => 'system-architecture-' . uniqid(),
            'content_md' => "# Architecture\nBuilt with Laravel 12.",
            'related_type' => 'global',
            'created_by' => $admin->id,
        ]);

        \App\Models\Document::create([
            'title' => 'Kickoff Notes',
            'slug' => 'kickoff-notes-' . uniqid(),
            'content_md' => 'Notes for task kickoff.',
            'related_type' => 'task',
            'related_id' => $task1->id,
            'created_by' => $user->id,
        ]);
    }
}
