<div class="space-y-6">
    <div>
        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">
            Project Name <span class="text-red-500">*</span>
        </label>
        <input type="text" id="name" name="name"
               value="{{ old('name', $project->name ?? '') }}"
               placeholder="e.g. Website Redesign"
               class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
        @error('name')
            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
        <textarea id="description" name="description" rows="4"
                  placeholder="Describe the project goals and scope…"
                  class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('description') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none">{{ old('description', $project->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-1.5">Start Date</label>
            <input type="date" id="start_date" name="start_date"
                   value="{{ old('start_date', isset($project) && $project->start_date ? $project->start_date->format('Y-m-d') : '') }}"
                   class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('start_date') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
            @error('start_date')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="deadline" class="block text-sm font-semibold text-gray-700 mb-1.5">Deadline</label>
            <input type="date" id="deadline" name="deadline"
                   value="{{ old('deadline', isset($project) && $project->deadline ? $project->deadline->format('Y-m-d') : '') }}"
                   class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('deadline') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
            @error('deadline')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
