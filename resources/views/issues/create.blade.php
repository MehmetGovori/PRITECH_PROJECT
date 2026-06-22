@extends('layouts.app')
@section('title', 'New Issue')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="text-sm text-gray-400 mb-2">
        @if($selectedProject)
            <a href="{{ route('projects.show', $selectedProject) }}" class="hover:text-indigo-600">{{ $selectedProject->name }}</a>
            &rsaquo; New Issue
        @else
            <a href="{{ route('issues.index') }}" class="hover:text-indigo-600">Issues</a>
            &rsaquo; New Issue
        @endif
    </div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">New Issue</h1>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <form method="POST" action="{{ route('issues.store') }}" novalidate>
            @csrf
            @include('issues._form')

            <div class="flex gap-3 mt-6">
                <button type="submit"
                    class="px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    Create Issue
                </button>
                <a href="{{ $selectedProject ? route('projects.show', $selectedProject) : route('issues.index') }}"
                   class="px-5 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
