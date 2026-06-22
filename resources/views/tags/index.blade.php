@extends('layouts.app')
@section('title', 'Tags')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tags</h1>
            <p class="text-sm text-gray-500 mt-0.5">Organize and label issues with tags</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Create New Tag</h2>
        <div class="flex gap-3 items-start flex-wrap">
            <div class="flex-1 min-w-[200px] space-y-1.5">
                <input type="text" id="tag-name-input" placeholder="Tag name (must be unique)"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                <p id="tag-name-error" class="text-xs text-red-600 hidden flex items-center gap-1"></p>
            </div>
            <div class="flex items-center gap-2">
                <div class="relative">
                    <input type="color" id="tag-color-input" value="#6366F1"
                           class="w-10 h-10 rounded-lg cursor-pointer border-2 border-gray-200 p-0.5"
                           title="Pick a color">
                </div>
                <span id="tag-color-hex" class="text-xs font-mono text-gray-500 w-16">#6366F1</span>
            </div>
            <button type="button" id="tag-create-btn"
                    class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm shrink-0">
                Create Tag
            </button>
        </div>
        <p id="tag-success" class="mt-3 text-sm text-emerald-600 font-medium hidden flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span id="tag-success-text"></span>
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <span class="text-sm font-semibold text-gray-700">All Tags</span>
            <span class="text-xs text-gray-400">{{ $tags->total() }} tags</span>
        </div>
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tag</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Color</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Issues</th>
                </tr>
            </thead>
            <tbody id="tags-table-body" class="divide-y divide-gray-100">
                @forelse($tags as $tag)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold text-white shadow-sm"
                              style="background-color: {{ $tag->color ?? '#6B7280' }}">
                            {{ $tag->name }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-md border border-gray-200 shadow-sm shrink-0"
                                  style="background-color: {{ $tag->color ?? '#6B7280' }}"></span>
                            <span class="text-sm font-mono text-gray-500">{{ $tag->color ?? '—' }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                            {{ $tag->issues_count }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-5 py-10 text-center text-sm text-gray-400">No tags yet. Create one above.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $tags->links() }}</div>
</div>

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

const colorInput = document.getElementById('tag-color-input');
const hexSpan    = document.getElementById('tag-color-hex');
colorInput.addEventListener('input', () => { hexSpan.textContent = colorInput.value; });

document.getElementById('tag-create-btn').addEventListener('click', async function() {
    const nameInput  = document.getElementById('tag-name-input');
    const errEl      = document.getElementById('tag-name-error');
    const successEl  = document.getElementById('tag-success');
    const successTxt = document.getElementById('tag-success-text');
    errEl.classList.add('hidden');
    successEl.classList.add('hidden');

    const res = await fetch('/tags', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify({ name: nameInput.value, color: colorInput.value }),
    });

    if (res.status === 422) {
        const data = await res.json();
        errEl.textContent = data.errors?.name?.[0] ?? 'Validation error.';
        errEl.classList.remove('hidden');
        return;
    }

    if (res.ok) {
        const { tag }    = await res.json();
        nameInput.value  = '';
        successTxt.textContent = `Tag "${tag.name}" created successfully!`;
        successEl.classList.remove('hidden');

        const tbody = document.getElementById('tags-table-body');
        const empty = tbody.querySelector('td[colspan]');
        if (empty) empty.closest('tr').remove();

        const row = document.createElement('tr');
        row.className = 'hover:bg-slate-50 transition-colors';
        row.innerHTML = `
            <td class="px-5 py-3.5">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold text-white shadow-sm"
                      style="background-color:${tag.color ?? '#6B7280'}">
                    ${tag.name}
                </span>
            </td>
            <td class="px-4 py-3.5">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-md border border-gray-200 shadow-sm shrink-0"
                          style="background-color:${tag.color ?? '#6B7280'}"></span>
                    <span class="text-sm font-mono text-gray-500">${tag.color ?? '—'}</span>
                </div>
            </td>
            <td class="px-4 py-3.5">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">0</span>
            </td>`;
        tbody.prepend(row);

        setTimeout(() => successEl.classList.add('hidden'), 3000);
    }
});
</script>
@endpush
@endsection
