@extends('layouts.app')
@section('title', $project->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('projects.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 mb-5 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-xl hover:bg-indigo-50 hover:border-indigo-400 hover:text-indigo-700 transition-all shadow-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        All Projects
    </a>

    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <h1 class="text-3xl font-extrabold text-gray-900 leading-tight tracking-tight">{{ $project->name }}</h1>
            @if($project->description)
                <p class="text-gray-600 mt-2 text-base leading-relaxed max-w-2xl">{{ $project->description }}</p>
            @endif
            <div class="flex flex-wrap gap-4 mt-3 text-xs text-gray-500">
                @if($project->start_date)
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Start: {{ $project->start_date->format('M j, Y') }}
                    </span>
                @endif
                @if($project->deadline)
                    <span class="flex items-center gap-1.5 {{ $project->deadline->isPast() ? 'text-red-500 font-semibold' : '' }}">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Deadline: {{ $project->deadline->format('M j, Y') }}
                        @if($project->deadline->isPast()) — Overdue @endif
                    </span>
                @endif
                <span class="flex items-center gap-1.5">
                    <div class="w-4 h-4 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center">
                        {{ strtoupper(substr($project->owner->name, 0, 1)) }}
                    </div>
                    {{ $project->owner->name }}
                </span>
            </div>
        </div>

        <div class="flex gap-2 shrink-0">
            <a href="{{ route('issues.create', ['project_id' => $project->id]) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                New Issue
            </a>
            @can('update', $project)
            <a href="{{ route('projects.edit', $project) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <form method="POST" action="{{ route('projects.destroy', $project) }}"
                  onsubmit="return confirm('Delete this project and all its issues?')">
                @csrf @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-50 hover:border-red-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                </button>
            </form>
            @endcan
        </div>
    </div>
</div>

@if($project->issues->isEmpty())
    <div class="text-center py-16 bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-50 mb-3">
            <svg class="w-7 h-7 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <h3 class="text-base font-semibold text-gray-900 mb-1">No issues yet</h3>
        <p class="text-sm text-gray-500 mb-4">Start tracking work by creating the first issue</p>
        <a href="{{ route('issues.create', ['project_id' => $project->id]) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
            Create First Issue
        </a>
    </div>
@else
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <span class="text-sm font-semibold text-gray-700">Issues</span>
            <span class="text-xs text-gray-400">{{ $project->issues->count() }} total</span>
        </div>
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Issue</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Priority</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider hidden sm:table-cell">Tags</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider hidden md:table-cell">Due</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($project->issues as $issue)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <a href="{{ route('issues.show', $issue) }}"
                           class="text-sm font-medium text-gray-900 hover:text-indigo-600 transition-colors">
                            {{ $issue->title }}
                        </a>
                    </td>
                    <td class="px-4 py-3.5">
                        @include('partials.status-badge', ['status' => $issue->status])
                    </td>
                    <td class="px-4 py-3.5">
                        @include('partials.priority-badge', ['priority' => $issue->priority])
                    </td>
                    <td class="px-4 py-3.5 hidden sm:table-cell">
                        <div class="flex flex-wrap gap-1">
                            @foreach($issue->tags as $tag)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium text-white"
                                      style="background-color: {{ $tag->color ?? '#6B7280' }}">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-4 py-3.5 text-sm text-gray-400 hidden md:table-cell">
                        @if($issue->due_date)
                            <span class="{{ $issue->due_date->isPast() && $issue->status !== 'closed' ? 'text-red-500 font-medium' : '' }}">
                                {{ $issue->due_date->format('M j, Y') }}
                            </span>
                        @else
                            —
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
