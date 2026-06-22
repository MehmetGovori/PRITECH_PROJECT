@extends('layouts.app')
@section('title', 'Tags')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tags</h1>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Create New Tag</h2>
        <div class="flex gap-3 items-start" id="tag-create-wrapper">
            <div class="flex-1 space-y-1">
                <input type="text" id="tag-name-input" placeholder="Tag name (unique)"
                       class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <p id="tag-name-error" class="text-xs text-red-600 hidden"></p>
            </div>
            <div class="flex items-center gap-2">
                <input type="color" id="tag-color-input" value="#6366F1"
                       class="h-9 w-12 rounded cursor-pointer border border-gray-300"
                       title="Pick a color">
                <span id="tag-color-hex" class="text-xs text-gray-500 w-16">#6366F1</span>
            </div>
            <button type="button" id="tag-create-btn"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shrink-0">
                Create Tag
            </button>
        </div>
        <p id="tag-success" class="mt-2 text-xs text-green-600 hidden"></p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tag</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Color</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Issues</th>
                </tr>
            </thead>
            <tbody id="tags-table-body" class="divide-y divide-gray-100">
                @foreach($tags as $tag)
                <tr>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white"
                              style="background-color: {{ $tag->color ?? '#6B7280' }}">
                            {{ $tag->name }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500 font-mono">{{ $tag->color ?? '—' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $tag->issues_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $tags->links() }}</div>
</div>

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

const colorInput = document.getElementById('tag-color-input');
const hexSpan    = document.getElementById('tag-color-hex');
colorInput.addEventListener('input', () => { hexSpan.textContent = colorInput.value; });

document.getElementById('tag-create-btn').addEventListener('click', async function() {
    const nameInput = document.getElementById('tag-name-input');
    const errEl     = document.getElementById('tag-name-error');
    const successEl = document.getElementById('tag-success');
    errEl.classList.add('hidden');
    successEl.classList.add('hidden');

    const res = await fetch('/tags', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept':       'application/json',
        },
        body: JSON.stringify({ name: nameInput.value, color: colorInput.value }),
    });

    if (res.status === 422) {
        const data    = await res.json();
        errEl.textContent = data.errors?.name?.[0] ?? 'Validation error.';
        errEl.classList.remove('hidden');
        return;
    }

    if (res.ok) {
        const { tag }   = await res.json();
        nameInput.value = '';
        successEl.textContent = `Tag "${tag.name}" created!`;
        successEl.classList.remove('hidden');

        const tbody = document.getElementById('tags-table-body');
        const row   = document.createElement('tr');
        row.innerHTML = `
            <td class="px-4 py-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white"
                      style="background-color:${tag.color ?? '#6B7280'}">
                    ${tag.name}
                </span>
            </td>
            <td class="px-4 py-3 text-sm text-gray-500 font-mono">${tag.color ?? '—'}</td>
            <td class="px-4 py-3 text-sm text-gray-500">0</td>
        `;
        tbody.prepend(row);
    }
});
</script>
@endpush
@endsection
