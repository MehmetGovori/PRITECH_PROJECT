@php
$map = [
    'open'        => ['bg-sky-100 text-sky-700',     'Open'],
    'in_progress' => ['bg-amber-100 text-amber-700', 'In Progress'],
    'closed'      => ['bg-emerald-100 text-emerald-700', 'Closed'],
];
[$class, $label] = $map[$status] ?? ['bg-gray-100 text-gray-600', ucfirst($status)];
@endphp
<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $class }}">
    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
    {{ $label }}
</span>
