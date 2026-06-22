<div class="space-y-5">
    <div>
        <label for="project_id" class="block text-sm font-medium text-gray-700 mb-1">Project <span class="text-red-500">*</span></label>
        <select id="project_id" name="project_id"
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm
                       {{ $errors->has('project_id') ? 'border-red-400' : '' }}">
            <option value="">— Select Project —</option>
            @foreach($projects as $project)
                <option value="{{ $project->id }}"
                    {{ old('project_id', $selectedProject->id ?? ($issue->project_id ?? '')) == $project->id ? 'selected' : '' }}>
                    {{ $project->name }}
                </option>
            @endforeach
        </select>
        @error('project_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
        <input type="text" id="title" name="title"
               value="{{ old('title', $issue->title ?? '') }}"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm
                      {{ $errors->has('title') ? 'border-red-400' : '' }}">
        @error('title')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea id="description" name="description" rows="5"
                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('description', $issue->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
            <select id="status" name="status"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                @foreach(['open' => 'Open', 'in_progress' => 'In Progress', 'closed' => 'Closed'] as $val => $label)
                    <option value="{{ $val }}" {{ old('status', $issue->status ?? 'open') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority <span class="text-red-500">*</span></label>
            <select id="priority" name="priority"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $val => $label)
                    <option value="{{ $val }}" {{ old('priority', $issue->priority ?? 'medium') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('priority')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
        <input type="date" id="due_date" name="due_date"
               value="{{ old('due_date', isset($issue) && $issue->due_date ? $issue->due_date->format('Y-m-d') : '') }}"
               class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        @error('due_date')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
