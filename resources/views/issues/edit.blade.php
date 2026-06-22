@extends('layouts.app')
@section('title', 'Edit Issue')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('issues.show', $issue) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Issue
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Edit Issue</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ Str::limit($issue->title, 60) }}</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <form method="POST" action="{{ route('issues.update', $issue) }}" novalidate>
            @csrf @method('PUT')
            @include('issues._form')

            <div class="flex gap-3 mt-8 pt-6 border-t border-gray-100">
                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                    Save Changes
                </button>
                <a href="{{ route('issues.show', $issue) }}"
                   class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
