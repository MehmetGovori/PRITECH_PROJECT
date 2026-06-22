@extends('layouts.app')
@section('title', 'Issues')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Issues</h1>
    <a href="{{ route('issues.create') }}"
       class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
        + New Issue
    </a>
</div>

<form method="GET" class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-6 flex flex-wrap gap-3 items-end">
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
               placeholder="Title or description…"
               id="issue-search"
               class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 w-52">
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
        <select name="status" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">All</option>
            @foreach(['open','in_progress','closed'] as $s)
                <option value="{{ $s }}" {{ ($filters['status'] ?? '') === $s ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_',' ',$s)) }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Priority</label>
        <select name="priority" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">All</option>
            @foreach(['low','medium','high'] as $p)
                <option value="{{ $p }}" {{ ($filters['priority'] ?? '') === $p ? 'selected' : '' }}>
                    {{ ucfirst($p) }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Tag</label>
        <select name="tag_id" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">All</option>
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}" {{ ($filters['tag_id'] ?? '') == $tag->id ? 'selected' : '' }}>
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>
    </div>
    <button type="submit"
        class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition">
        Filter
    </button>
    @if(array_filter($filters))
        <a href="{{ route('issues.index') }}"
           class="px-4 py-2 text-sm text-gray-500 hover:text-gray-800">Clear</a>
    @endif
</form>

<div id="issues-container">
@if($issues->isEmpty())
    <div class="text-center py-12 text-gray-400 bg-white rounded-xl border border-gray-200">
        No issues found.
    </div>
@else
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Project</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Priority</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tags</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Due</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($issues as $issue)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        <a href="{{ route('issues.show', $issue) }}"
                           class="text-sm font-medium text-gray-900 hover:text-indigo-600">
                            {{ $issue->title }}
                        </a>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">
                        <a href="{{ route('projects.show', $issue->project) }}" class="hover:text-indigo-600">
                            {{ $issue->project->name }}
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
                        {{ $issue->due_date ? $issue->due_date->format('M j') : '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $issues->withQueryString()->links() }}</div>
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
            const form   = this.closest('form');
            const params = new URLSearchParams(new FormData(form));
            fetch('{{ route("issues.index") }}?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.text())
            .then(html => {
                const doc       = new DOMParser().parseFromString(html, 'text/html');
                const container = doc.getElementById('issues-container');
                if (container) {
                    document.getElementById('issues-container').innerHTML = container.innerHTML;
                }
            });
        }, 400);
    });
}
</script>
@endpush
@endsection
