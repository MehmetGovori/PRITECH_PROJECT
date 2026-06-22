<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Issue Tracker') }} — @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

<nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-6">
                <a href="{{ route('projects.index') }}" class="text-indigo-600 font-bold text-lg tracking-tight">
                    🗂 Issue Tracker
                </a>
                <a href="{{ route('projects.index') }}"
                   class="text-sm {{ request()->routeIs('projects.*') ? 'text-indigo-600 font-semibold' : 'text-gray-500 hover:text-gray-800' }}">
                    Projects
                </a>
                <a href="{{ route('issues.index') }}"
                   class="text-sm {{ request()->routeIs('issues.*') ? 'text-indigo-600 font-semibold' : 'text-gray-500 hover:text-gray-800' }}">
                    Issues
                </a>
                <a href="{{ route('tags.index') }}"
                   class="text-sm {{ request()->routeIs('tags.*') ? 'text-indigo-600 font-semibold' : 'text-gray-500 hover:text-gray-800' }}">
                    Tags
                </a>
            </div>
            <div class="flex items-center gap-4">
                @auth
                    <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-red-600">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>

@stack('modals')
@stack('scripts')
</body>
</html>
