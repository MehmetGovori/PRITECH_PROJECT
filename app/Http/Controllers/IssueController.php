<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IssueController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['status', 'priority', 'tag_id', 'search']);

        $issues = Issue::with(['project', 'tags'])
            ->filter($filters)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $tags = Tag::orderBy('name')->get();

        return view('issues.index', compact('issues', 'tags', 'filters'));
    }

    public function create(Request $request): View
    {
        $projects = Project::orderBy('name')->get();
        $selectedProject = $request->query('project_id')
            ? Project::findOrFail($request->query('project_id'))
            : null;

        return view('issues.create', compact('projects', 'selectedProject'));
    }

    public function store(StoreIssueRequest $request): RedirectResponse
    {
        $issue = Issue::create($request->validated());

        return redirect()->route('issues.show', $issue)
            ->with('success', 'Issue created successfully.');
    }

    public function show(Issue $issue): View
    {
        $issue->load(['project', 'tags', 'assignees']);
        $tags = Tag::orderBy('name')->get();
        $users = \App\Models\User::orderBy('name')->get();

        return view('issues.show', compact('issue', 'tags', 'users'));
    }

    public function edit(Issue $issue): View
    {
        $projects = Project::orderBy('name')->get();

        return view('issues.edit', compact('issue', 'projects'));
    }

    public function update(UpdateIssueRequest $request, Issue $issue): RedirectResponse
    {
        $issue->update($request->validated());

        return redirect()->route('issues.show', $issue)
            ->with('success', 'Issue updated successfully.');
    }

    public function destroy(Issue $issue): RedirectResponse
    {
        $projectId = $issue->project_id;
        $issue->delete();

        return redirect()->route('projects.show', $projectId)
            ->with('success', 'Issue deleted.');
    }
}
