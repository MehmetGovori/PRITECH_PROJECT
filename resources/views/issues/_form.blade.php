<div class="space-y-6">
    <div>
        <label for="project_id" class="block text-sm font-semibold text-gray-700 mb-1.5">
            Project <span class="text-red-500">*</span>
        </label>
        <select id="project_id" name="project_id"
                class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('project_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
            <option value="">— Select a project —</option>
            @foreach($projects as $project)
                <option value="{{ $project->id }}"
                    {{ old('project_id', $selectedProject->id ?? ($issue->project_id ?? '')) == $project->id ? 'selected' : '' }}>
                    {{ $project->name }}
                </option>
            @endforeach
        </select>
        @error('project_id')
            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <div>
        <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">
            Title <span class="text-red-500">*</span>
        </label>
        <input type="text" id="title" name="title"
               value="{{ old('title', $issue->title ?? '') }}"
               placeholder="Short, descriptive issue title"
               class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('title') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
        @error('title')
            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
        <textarea id="description" name="description" rows="5"
                  placeholder="Describe the issue in detail…"
                  class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('description') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none">{{ old('description', $issue->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="status" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Status <span class="text-red-500">*</span>
            </label>
            <select id="status" name="status"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                @foreach(['open' => 'Open', 'in_progress' => 'In Progress', 'closed' => 'Closed'] as $val => $label)
                    <option value="{{ $val }}" {{ old('status', $issue->status ?? 'open') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="priority" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Priority <span class="text-red-500">*</span>
            </label>
            <select id="priority" name="priority"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $val => $label)
                    <option value="{{ $val }}" {{ old('priority', $issue->priority ?? 'medium') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('priority')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="due_date" class="block text-sm font-semibold text-gray-700 mb-1.5">Due Date</label>
        <input type="date" id="due_date" name="due_date"
               value="{{ old('due_date', isset($issue) && $issue->due_date ? $issue->due_date->format('Y-m-d') : '') }}"
               class="w-full sm:w-56 px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
        @error('due_date')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
