@extends('layouts.app')
@section('title', $issue->title)

@section('content')
<div class="flex items-start justify-between mb-6">
    <div class="flex-1 min-w-0">
        <div class="text-sm text-gray-400 mb-1">
            <a href="{{ route('projects.show', $issue->project) }}" class="hover:text-indigo-600">{{ $issue->project->name }}</a>
            &rsaquo; Issue #{{ $issue->id }}
        </div>
        <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $issue->title }}</h1>
        <div class="flex items-center gap-3 mt-2">
            @include('partials.status-badge', ['status' => $issue->status])
            @include('partials.priority-badge', ['priority' => $issue->priority])
            @if($issue->due_date)
                <span class="text-xs text-gray-400">
                    Due {{ $issue->due_date->format('M j, Y') }}
                    @if($issue->due_date->isPast() && $issue->status !== 'closed')
                        <span class="text-red-500 font-semibold">(overdue)</span>
                    @endif
                </span>
            @endif
        </div>
    </div>
    <div class="flex gap-2 shrink-0 ml-4">
        <a href="{{ route('issues.edit', $issue) }}"
           class="px-3 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">Edit</a>
        <form method="POST" action="{{ route('issues.destroy', $issue) }}"
              onsubmit="return confirm('Delete this issue?')">
            @csrf @method('DELETE')
            <button type="submit"
                class="px-3 py-2 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 transition">Delete</button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Description</h2>
            @if($issue->description)
                <div class="prose prose-sm max-w-none text-gray-700">{{ $issue->description }}</div>
            @else
                <p class="text-sm text-gray-400">No description provided.</p>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Comments</h2>

            <form id="comment-form" class="mb-6 space-y-3" novalidate>
                @csrf
                <div>
                    <input type="text" id="comment-author" name="author_name"
                           placeholder="Your name"
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p id="err-author" class="mt-1 text-xs text-red-600 hidden"></p>
                </div>
                <div>
                    <textarea id="comment-body" name="body" rows="3"
                              placeholder="Write a comment…"
                              class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    <p id="err-body" class="mt-1 text-xs text-red-600 hidden"></p>
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    Add Comment
                </button>
            </form>

            <div id="comments-list" class="space-y-4">
                <div class="text-center text-gray-400 text-sm py-4" id="comments-loading">Loading comments…</div>
            </div>
            <div id="comments-pagination" class="mt-4 flex justify-center gap-2"></div>
        </div>
    </div>

    <div class="space-y-5">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-700">Tags</h3>
                <button type="button" id="tag-modal-btn"
                        class="text-xs text-indigo-600 hover:underline">Manage</button>
            </div>
            <div id="issue-tags" class="flex flex-wrap gap-1 min-h-[24px]">
                @foreach($issue->tags as $tag)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium text-white"
                          style="background-color: {{ $tag->color ?? '#6B7280' }}"
                          data-tag-id="{{ $tag->id }}">
                        {{ $tag->name }}
                    </span>
                @endforeach
                @if($issue->tags->isEmpty())
                    <span class="text-xs text-gray-400" id="no-tags-msg">No tags attached.</span>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-700">Assignees</h3>
                <button type="button" id="assignee-modal-btn"
                        class="text-xs text-indigo-600 hover:underline">Manage</button>
            </div>
            <div id="issue-assignees" class="space-y-1">
                @foreach($issue->assignees as $assignee)
                    <div class="flex items-center gap-2 text-sm text-gray-700" data-user-id="{{ $assignee->id }}">
                        <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold flex items-center justify-center">
                            {{ strtoupper(substr($assignee->name, 0, 1)) }}
                        </span>
                        {{ $assignee->name }}
                    </div>
                @endforeach
                @if($issue->assignees->isEmpty())
                    <p class="text-xs text-gray-400" id="no-assignees-msg">No assignees.</p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 text-sm text-gray-500 space-y-2">
            <div class="flex justify-between">
                <span class="font-medium text-gray-600">Project</span>
                <a href="{{ route('projects.show', $issue->project) }}" class="text-indigo-600 hover:underline">
                    {{ $issue->project->name }}
                </a>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-600">Created</span>
                <span>{{ $issue->created_at->format('M j, Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-600">Updated</span>
                <span>{{ $issue->updated_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>
</div>

<div id="tag-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" id="tag-modal-backdrop"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative z-10">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Manage Tags</h3>
                <button type="button" id="tag-modal-close" class="text-gray-400 hover:text-gray-700 text-xl">&times;</button>
            </div>
            <div id="tag-list" class="grid grid-cols-2 gap-2 mb-4 max-h-64 overflow-y-auto">
                @foreach($tags as $tag)
                    @php $attached = $issue->tags->contains($tag->id); @endphp
                    <button type="button"
                            class="tag-toggle-btn flex items-center gap-2 px-3 py-2 rounded-lg border text-sm font-medium transition
                                   {{ $attached ? 'border-transparent text-white' : 'border-gray-200 text-gray-700 hover:border-gray-300' }}"
                            style="{{ $attached ? 'background-color:'.$tag->color.';' : '' }}"
                            data-tag-id="{{ $tag->id }}"
                            data-tag-name="{{ $tag->name }}"
                            data-tag-color="{{ $tag->color ?? '#6B7280' }}"
                            data-attached="{{ $attached ? '1' : '0' }}">
                        <span class="w-3 h-3 rounded-full shrink-0" style="background-color: {{ $tag->color ?? '#6B7280' }}"></span>
                        {{ $tag->name }}
                        @if($attached) <span class="ml-auto text-xs opacity-75">✓</span> @endif
                    </button>
                @endforeach
            </div>
            <hr class="mb-4">
            <p class="text-xs font-semibold text-gray-500 mb-2">Create new tag</p>
            <div class="flex gap-2 items-start" id="new-tag-form">
                <div class="flex-1 space-y-2">
                    <input type="text" id="new-tag-name" placeholder="Tag name"
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p id="new-tag-error" class="text-xs text-red-600 hidden"></p>
                </div>
                <input type="color" id="new-tag-color" value="#6366F1"
                       class="h-9 w-12 rounded cursor-pointer border border-gray-300">
                <button type="button" id="create-tag-btn"
                        class="px-3 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition shrink-0">
                    Add
                </button>
            </div>
        </div>
    </div>
</div>

<div id="assignee-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" id="assignee-modal-backdrop"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 relative z-10">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Manage Assignees</h3>
                <button type="button" id="assignee-modal-close" class="text-gray-400 hover:text-gray-700 text-xl">&times;</button>
            </div>
            <div class="space-y-2">
                @foreach($users as $user)
                    @php $isAssigned = $issue->assignees->contains($user->id); @endphp
                    <button type="button"
                            class="assignee-toggle-btn w-full flex items-center gap-3 px-3 py-2 rounded-lg border text-sm transition
                                   {{ $isAssigned ? 'border-indigo-300 bg-indigo-50 text-indigo-700 font-medium' : 'border-gray-200 text-gray-700 hover:border-gray-300' }}"
                            data-user-id="{{ $user->id }}"
                            data-user-name="{{ $user->name }}"
                            data-attached="{{ $isAssigned ? '1' : '0' }}">
                        <span class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold flex items-center justify-center shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                        {{ $user->name }}
                        @if($isAssigned) <span class="ml-auto text-xs text-indigo-500">✓ Assigned</span> @endif
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const CSRF    = document.querySelector('meta[name="csrf-token"]').content;
const ISSUE_ID = {{ $issue->id }};
const ROUTES  = {
    toggleTag:      '/issues/' + ISSUE_ID + '/tags/toggle',
    toggleAssignee: '/issues/' + ISSUE_ID + '/users/toggle',
    storeTags:      '/tags',
    comments:       '/issues/' + ISSUE_ID + '/comments',
};

let currentPage = 1;

async function loadComments(page = 1) {
    const res  = await fetch(ROUTES.comments + '?page=' + page, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    const data = await res.json();
    renderComments(data);
    currentPage = page;
}

function renderComments(data) {
    const list = document.getElementById('comments-list');
    if (!data.data || data.data.length === 0) {
        list.innerHTML = '<p class="text-sm text-gray-400 text-center py-4">No comments yet. Be the first!</p>';
        document.getElementById('comments-pagination').innerHTML = '';
        return;
    }
    list.innerHTML = data.data.map(c => commentHtml(c)).join('');

    const pag = document.getElementById('comments-pagination');
    pag.innerHTML = '';
    if (data.last_page > 1) {
        for (let p = 1; p <= data.last_page; p++) {
            const btn = document.createElement('button');
            btn.textContent = p;
            btn.className = 'px-3 py-1 rounded text-sm border ' + (p === data.current_page
                ? 'bg-indigo-600 text-white border-indigo-600'
                : 'border-gray-300 text-gray-600 hover:bg-gray-50');
            btn.addEventListener('click', () => loadComments(p));
            pag.appendChild(btn);
        }
    }
}

function commentHtml(c) {
    return `<div class="flex gap-3 p-4 rounded-lg bg-gray-50 border border-gray-100">
        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center shrink-0">
            ${c.author_name.charAt(0).toUpperCase()}
        </div>
        <div>
            <div class="text-xs font-semibold text-gray-700">${escHtml(c.author_name)}
                <span class="font-normal text-gray-400 ml-2">${c.created_at}</span>
            </div>
            <p class="text-sm text-gray-700 mt-1 whitespace-pre-wrap">${escHtml(c.body)}</p>
        </div>
    </div>`;
}

function escHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

document.getElementById('comment-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const authorEl  = document.getElementById('comment-author');
    const bodyEl    = document.getElementById('comment-body');
    const errAuthor = document.getElementById('err-author');
    const errBody   = document.getElementById('err-body');

    errAuthor.classList.add('hidden');
    errBody.classList.add('hidden');

    const res = await fetch(ROUTES.comments, {
        method: 'POST',
        headers: {
            'Content-Type':     'application/json',
            'X-CSRF-TOKEN':     CSRF,
            'Accept':           'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ author_name: authorEl.value, body: bodyEl.value }),
    });

    if (res.status === 422) {
        const data = await res.json();
        if (data.errors?.author_name) { errAuthor.textContent = data.errors.author_name[0]; errAuthor.classList.remove('hidden'); }
        if (data.errors?.body)        { errBody.textContent   = data.errors.body[0];        errBody.classList.remove('hidden'); }
        return;
    }

    if (res.ok) {
        const data = await res.json();
        const list = document.getElementById('comments-list');
        const noMsg = list.querySelector('p');
        if (noMsg) noMsg.remove();
        list.insertAdjacentHTML('afterbegin', commentHtml(data.comment));
        authorEl.value = '';
        bodyEl.value   = '';
    }
});

loadComments(1);

function openModal(id)  { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

document.getElementById('tag-modal-btn').addEventListener('click',     () => openModal('tag-modal'));
document.getElementById('tag-modal-close').addEventListener('click',   () => closeModal('tag-modal'));
document.getElementById('tag-modal-backdrop').addEventListener('click',() => closeModal('tag-modal'));

async function toggleTag(tagId) {
    const res  = await fetch(ROUTES.toggleTag, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify({ tag_id: tagId }),
    });
    const data = await res.json();
    syncTagsDisplay(data.tags);
    return data.attached;
}

function syncTagsDisplay(tags) {
    const container = document.getElementById('issue-tags');
    const noMsg     = document.getElementById('no-tags-msg');
    if (noMsg) noMsg.remove();
    container.querySelectorAll('[data-tag-id]').forEach(el => el.remove());

    if (tags.length === 0) {
        container.insertAdjacentHTML('afterbegin', '<span class="text-xs text-gray-400" id="no-tags-msg">No tags attached.</span>');
        return;
    }
    tags.forEach(tag => {
        container.insertAdjacentHTML('beforeend',
            `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium text-white"
                  style="background-color:${tag.color ?? '#6B7280'}" data-tag-id="${tag.id}">
                ${escHtml(tag.name)}
            </span>`
        );
    });
}

document.getElementById('tag-list').addEventListener('click', async function(e) {
    const btn = e.target.closest('.tag-toggle-btn');
    if (!btn) return;
    btn.disabled   = true;
    const tagId    = btn.dataset.tagId;
    const tagColor = btn.dataset.tagColor;
    const attached = await toggleTag(parseInt(tagId));

    btn.dataset.attached = attached ? '1' : '0';
    if (attached) {
        btn.style.backgroundColor = tagColor;
        btn.classList.add('text-white', 'border-transparent');
        btn.classList.remove('border-gray-200', 'text-gray-700');
        if (!btn.querySelector('.check-mark')) btn.insertAdjacentHTML('beforeend', '<span class="check-mark ml-auto text-xs opacity-75">✓</span>');
    } else {
        btn.style.backgroundColor = '';
        btn.classList.remove('text-white', 'border-transparent');
        btn.classList.add('border-gray-200', 'text-gray-700');
        btn.querySelector('.check-mark')?.remove();
    }
    btn.disabled = false;
});

document.getElementById('create-tag-btn').addEventListener('click', async function() {
    const nameEl  = document.getElementById('new-tag-name');
    const colorEl = document.getElementById('new-tag-color');
    const errEl   = document.getElementById('new-tag-error');
    errEl.classList.add('hidden');

    const res = await fetch(ROUTES.storeTags, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify({ name: nameEl.value, color: colorEl.value }),
    });

    if (res.status === 422) {
        const data = await res.json();
        errEl.textContent = data.errors?.name?.[0] ?? 'Validation error.';
        errEl.classList.remove('hidden');
        return;
    }

    if (res.ok) {
        const { tag } = await res.json();
        nameEl.value   = '';
        document.getElementById('tag-list').insertAdjacentHTML('beforeend',
            `<button type="button"
                class="tag-toggle-btn flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:border-gray-300 text-sm font-medium transition"
                data-tag-id="${tag.id}" data-tag-name="${escHtml(tag.name)}" data-tag-color="${tag.color}" data-attached="0">
                <span class="w-3 h-3 rounded-full shrink-0" style="background-color:${tag.color}"></span>
                ${escHtml(tag.name)}
            </button>`
        );
    }
});

document.getElementById('assignee-modal-btn').addEventListener('click',     () => openModal('assignee-modal'));
document.getElementById('assignee-modal-close').addEventListener('click',   () => closeModal('assignee-modal'));
document.getElementById('assignee-modal-backdrop').addEventListener('click',() => closeModal('assignee-modal'));

document.getElementById('assignee-modal').addEventListener('click', async function(e) {
    const btn = e.target.closest('.assignee-toggle-btn');
    if (!btn) return;
    btn.disabled   = true;
    const userId   = parseInt(btn.dataset.userId);

    const res = await fetch(ROUTES.toggleAssignee, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify({ user_id: userId }),
    });
    const data = await res.json();

    btn.dataset.attached = data.attached ? '1' : '0';
    if (data.attached) {
        btn.classList.add('border-indigo-300', 'bg-indigo-50', 'text-indigo-700', 'font-medium');
        btn.classList.remove('border-gray-200', 'text-gray-700');
        if (!btn.querySelector('.assigned-mark'))
            btn.insertAdjacentHTML('beforeend', '<span class="assigned-mark ml-auto text-xs text-indigo-500">✓ Assigned</span>');
    } else {
        btn.classList.remove('border-indigo-300', 'bg-indigo-50', 'text-indigo-700', 'font-medium');
        btn.classList.add('border-gray-200', 'text-gray-700');
        btn.querySelector('.assigned-mark')?.remove();
    }

    syncAssigneesDisplay(data.assignees);
    btn.disabled = false;
});

function syncAssigneesDisplay(assignees) {
    const container = document.getElementById('issue-assignees');
    container.innerHTML = '';
    if (!assignees || assignees.length === 0) {
        container.innerHTML = '<p class="text-xs text-gray-400" id="no-assignees-msg">No assignees.</p>';
        return;
    }
    assignees.forEach(u => {
        container.insertAdjacentHTML('beforeend',
            `<div class="flex items-center gap-2 text-sm text-gray-700" data-user-id="${u.id}">
                <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold flex items-center justify-center">
                    ${escHtml(u.name.charAt(0).toUpperCase())}
                </span>
                ${escHtml(u.name)}
            </div>`
        );
    });
}
</script>
@endpush
@endsection
