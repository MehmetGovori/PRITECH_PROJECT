@extends('layouts.app')
@section('title', $project->name)

@section('content')
<div class="flex items-start justify-between mb-6">
    <div>
        <div class="text-sm text-gray-400 mb-1">
            <a href="{{ route('projects.index') }}" class="hover:text-indigo-600">Projects</a>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $project->name }}</h1>
        @if($project->description)
            <p class="text-gray-500 mt-1 text-sm">{{ $project->description }}</p>
        @endif
        <div class="flex gap-4 mt-2 text-xs text-gray-400">
            @if($project->start_date) <span>Start: {{ $project->start_date->format('M j, Y') }}</span> @endif
            @if($project->deadline)
                <span class="{{ $project->deadline->isPast() ? 'text-red-500 font-semibold' : '' }}">
                    Deadline: {{ $project->deadline->format('M j, Y') }}
                </span>
            @endif
            <span>Owner: {{ $project->owner->name }}</span>
        </div>
    </div>
    <div class="flex gap-2 shrink-0">
        <a href="{{ route('issues.create', ['project_id' => $project->id]) }}"
           class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
            + New Issue
        </a>
        @can('update', $project)
        <a href="{{ route('projects.edit', $project) }}"
           class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
            Edit
        </a>
        <form method="POST" action="{{ route('projects.destroy', $project) }}"
              onsubmit="return confirm('Delete this project and all its issues?')">
            @csrf @method('DELETE')
            <button type="submit"
                class="px-4 py-2 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 transition">
                Delete
            </button>
        </form>
        @endcan
    </div>
</div>

@if($project->issues->isEmpty())
    <div class="text-center py-12 text-gray-400 bg-white rounded-xl border border-gray-200">
        <p>No issues yet.</p>
        <a href="{{ route('issues.create', ['project_id' => $project->id]) }}"
           class="mt-1 inline-block text-indigo-600 hover:underline text-sm">Create the first issue</a>
    </div>
@else
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Issue</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Priority</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tags</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Due</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($project->issues as $issue)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        <a href="{{ route('issues.show', $issue) }}"
                           class="text-sm font-medium text-gray-900 hover:text-indigo-600">
                            {{ $issue->title }}
                        </a>
                    </td>
                    <td class="px-4 py-3">
                        @include('partials.status-badge', ['status' => $issue->status])
                    </td>
                    <td class="px-4 py-3">
                        @include('partials.priority-badge', ['priority' => $issue->priority])
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            @foreach($issue->tags as $tag)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium text-white"
                                      style="background-color: {{ $tag->color ?? '#6B7280' }}">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-400">
                        {{ $issue->due_date ? $issue->due_date->format('M j, Y') : '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
