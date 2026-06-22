@extends('layouts.app')
@section('title', 'Projects')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Projects</h1>
    <a href="{{ route('projects.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
        + New Project
    </a>
</div>

@if($projects->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <p class="text-lg">No projects yet.</p>
        <a href="{{ route('projects.create') }}" class="mt-2 inline-block text-indigo-600 hover:underline">Create your first project</a>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($projects as $project)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition p-5 flex flex-col gap-3">
            <div class="flex items-start justify-between">
                <a href="{{ route('projects.show', $project) }}"
                   class="text-lg font-semibold text-gray-900 hover:text-indigo-600 leading-tight">
                    {{ $project->name }}
                </a>
                <span class="text-xs text-gray-400">{{ $project->issues_count }} issue{{ $project->issues_count !== 1 ? 's' : '' }}</span>
            </div>

            @if($project->description)
                <p class="text-sm text-gray-500 line-clamp-2">{{ $project->description }}</p>
            @endif

            <div class="text-xs text-gray-400 flex gap-4 mt-auto pt-2 border-t border-gray-100">
                @if($project->start_date)
                    <span>Start: {{ $project->start_date->format('M j, Y') }}</span>
                @endif
                @if($project->deadline)
                    <span class="{{ $project->deadline->isPast() ? 'text-red-500 font-medium' : '' }}">
                        Due: {{ $project->deadline->format('M j, Y') }}
                    </span>
                @endif
            </div>

            <div class="flex items-center justify-between mt-1">
                <span class="text-xs text-gray-400">by {{ $project->owner->name }}</span>
                <div class="flex gap-2">
                    @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}"
                       class="text-xs text-indigo-500 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('projects.destroy', $project) }}"
                          onsubmit="return confirm('Delete this project and all its issues?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 hover:underline">Delete</button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $projects->links() }}</div>
@endif
@endsection
