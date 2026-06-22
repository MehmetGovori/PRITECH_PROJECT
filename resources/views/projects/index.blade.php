@extends('layouts.app')
@section('title', 'Projects')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Projects</h1>
        <p class="text-sm text-gray-500 mt-0.5">Manage and track all your projects</p>
    </div>
    <a href="{{ route('projects.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        New Project
    </a>
</div>

@if($projects->isEmpty())
    <div class="text-center py-20 bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-50 mb-4">
            <svg class="w-8 h-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
            </svg>
        </div>
        <h3 class="text-base font-semibold text-gray-900 mb-1">No projects yet</h3>
        <p class="text-sm text-gray-500 mb-4">Get started by creating your first project</p>
        <a href="{{ route('projects.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
            Create Project
        </a>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($projects as $project)
        <div class="group bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md hover:border-indigo-200 transition-all duration-200 flex flex-col overflow-hidden">
            <div class="p-5 flex-1 flex flex-col gap-3">
                <div class="flex items-start justify-between gap-2">
                    <a href="{{ route('projects.show', $project) }}"
                       class="text-base font-semibold text-gray-900 group-hover:text-indigo-600 leading-snug transition-colors">
                        {{ $project->name }}
                    </a>
                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                        {{ $project->issues_count }} {{ Str::plural('issue', $project->issues_count) }}
                    </span>
                </div>

                @if($project->description)
                    <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed">{{ $project->description }}</p>
                @endif

                @if($project->start_date || $project->deadline)
                <div class="flex flex-wrap gap-3 text-xs text-gray-400 pt-1">
                    @if($project->start_date)
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $project->start_date->format('M j, Y') }}
                        </span>
                    @endif
                    @if($project->deadline)
                        <span class="flex items-center gap-1 {{ $project->deadline->isPast() ? 'text-red-500 font-semibold' : '' }}">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Due {{ $project->deadline->format('M j, Y') }}
                        </span>
                    @endif
                </div>
                @endif
            </div>

            <div class="flex items-center justify-between px-5 py-3 border-t border-gray-100 bg-gray-50">
                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                    <div class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center">
                        {{ strtoupper(substr($project->owner->name, 0, 1)) }}
                    </div>
                    {{ $project->owner->name }}
                </div>
                @can('update', $project)
                <div class="flex items-center gap-1">
                    <a href="{{ route('projects.edit', $project) }}"
                       class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    <form method="POST" action="{{ route('projects.destroy', $project) }}"
                          onsubmit="return confirm('Delete this project and all its issues?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
                @endcan
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">{{ $projects->links() }}</div>
@endif
@endsection
