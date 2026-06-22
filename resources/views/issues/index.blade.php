@extends('layouts.app')
@section('title', 'Issues')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Issues</h1>
        <p class="text-sm text-gray-500 mt-0.5">Track bugs, features, and tasks across all projects</p>
    </div>
    <a href="{{ route('issues.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        New Issue
    </a>
</div>

<form method="GET" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-6">
    <div class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[180px]">
            <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Search</label>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                       placeholder="Search issues…"
                       id="issue-search"
                       class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
            </div>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Status</label>
            <select name="status" class="px-3 py-2 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white">
                <option value="">All Statuses</option>
                @foreach(['open' => 'Open', 'in_progress' => 'In Progress', 'closed' => 'Closed'] as $val => $label)
                    <option value="{{ $val }}" {{ ($filters['status'] ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Priority</label>
            <select name="priority" class="px-3 py-2 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white">
                <option value="">All Priorities</option>
                @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $val => $label)
                    <option value="{{ $val }}" {{ ($filters['priority'] ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Tag</label>
            <select name="tag_id" class="px-3 py-2 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white">
                <option value="">All Tags</option>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ ($filters['tag_id'] ?? '') == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit"
            class="px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-700 transition-colors">
            Filter
        </button>
        @if(array_filter($filters))
            <a href="{{ route('issues.index') }}"
               class="px-4 py-2 text-sm text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition-colors">
                Clear
            </a>
        @endif
    </div>
</form>

<div id="issues-container">
@if($issues->isEmpty())
    <div class="text-center py-16 bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gray-100 mb-3">
            <svg class="w-7 h-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <h3 class="text-base font-semibold text-gray-900 mb-1">No issues found</h3>
        <p class="text-sm text-gray-500">Try adjusting your filters or create a new issue</p>
    </div>
@else
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider hidden sm:table-cell">Project</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider hidden md:table-cell">Priority</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider hidden lg:table-cell">Tags</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider hidden lg:table-cell">Due</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($issues as $issue)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <a href="{{ route('issues.show', $issue) }}"
                           class="text-sm font-medium text-gray-900 hover:text-indigo-600 transition-colors">
                            {{ $issue->title }}
                        </a>
                    </td>
                    <td class="px-4 py-3.5 hidden sm:table-cell">
                        <a href="{{ route('projects.show', $issue->project) }}"
                           class="text-sm text-gray-500 hover:text-indigo-600 transition-colors">
                            {{ $issue->project->name }}
                        </a>
                    </td>
                    <td class="px-4 py-3.5">
                        @include('partials.status-badge', ['status' => $issue->status])
                    </td>
                    <td class="px-4 py-3.5 hidden md:table-cell">
                        @include('partials.priority-badge', ['priority' => $issue->priority])
                    </td>
                    <td class="px-4 py-3.5 hidden lg:table-cell">
                        <div class="flex flex-wrap gap-1">
                            @foreach($issue->tags as $tag)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium text-white"
                                      style="background-color: {{ $tag->color ?? '#6B7280' }}">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-4 py-3.5 hidden lg:table-cell text-sm text-gray-400">
                        @if($issue->due_date)
                            <span class="{{ $issue->due_date->isPast() && $issue->status !== 'closed' ? 'text-red-500 font-medium' : '' }}">
                                {{ $issue->due_date->format('M j') }}
                            </span>
                        @else —
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $issues->withQueryString()->links() }}</div>
@endif
</div>

@push('scripts')
<script>
let searchTimer;
const searchInput = document.getElementById('issue-search');
if (searchInput) {
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            const params = new URLSearchParams(new FormData(this.closest('form')));
            fetch('{{ route("issues.index") }}?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.text())
            .then(html => {
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const container = doc.getElementById('issues-container');
                if (container) document.getElementById('issues-container').innerHTML = container.innerHTML;
            });
        }, 400);
    });
}
</script>
@endpush
@endsection
