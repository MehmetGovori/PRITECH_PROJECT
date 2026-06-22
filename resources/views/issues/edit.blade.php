@extends('layouts.app')
@section('title', 'Edit Issue')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="text-sm text-gray-400 mb-2">
        <a href="{{ route('projects.show', $issue->project) }}" class="hover:text-indigo-600">{{ $issue->project->name }}</a>
        &rsaquo;
        <a href="{{ route('issues.show', $issue) }}" class="hover:text-indigo-600">{{ Str::limit($issue->title, 40) }}</a>
        &rsaquo; Edit
    </div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Issue</h1>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <form method="POST" action="{{ route('issues.update', $issue) }}" novalidate>
            @csrf @method('PUT')
            @include('issues._form')

            <div class="flex gap-3 mt-6">
                <button type="submit"
                    class="px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    Save Changes
                </button>
                <a href="{{ route('issues.show', $issue) }}"
                   class="px-5 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
