<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $alice = User::factory()->create([
            'name'  => 'Alice Owner',
            'email' => 'alice@example.com',
        ]);

        $bob = User::factory()->create([
            'name'  => 'Bob Developer',
            'email' => 'bob@example.com',
        ]);

        $carol = User::factory()->create([
            'name'  => 'Carol Designer',
            'email' => 'carol@example.com',
        ]);

        $users = collect([$alice, $bob, $carol]);

        $tagData = [
            ['name' => 'bug',         'color' => '#EF4444'],
            ['name' => 'feature',     'color' => '#3B82F6'],
            ['name' => 'enhancement', 'color' => '#8B5CF6'],
            ['name' => 'docs',        'color' => '#F59E0B'],
            ['name' => 'security',    'color' => '#EC4899'],
            ['name' => 'performance', 'color' => '#10B981'],
            ['name' => 'ui',          'color' => '#6366F1'],
            ['name' => 'backend',     'color' => '#0EA5E9'],
        ];

        $tags = collect($tagData)->map(fn($t) => Tag::create($t));

        $aliceProjects = Project::factory(3)->create(['user_id' => $alice->id]);
        $bobProjects   = Project::factory(2)->create(['user_id' => $bob->id]);
        $allProjects   = $aliceProjects->merge($bobProjects);

        $allProjects->each(function (Project $project) use ($tags, $users) {
            $issues = Issue::factory(rand(4, 8))->create(['project_id' => $project->id]);

            $issues->each(function (Issue $issue) use ($tags, $users) {
                $issue->tags()->attach($tags->random(rand(1, 3))->pluck('id'));
                $issue->assignees()->attach($users->random(rand(1, 2))->pluck('id'));
                Comment::factory(rand(1, 5))->create(['issue_id' => $issue->id]);
            });
        });
    }
}
