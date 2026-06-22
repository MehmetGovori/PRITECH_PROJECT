@php
$colors = [
    'open'        => 'bg-blue-100 text-blue-700',
    'in_progress' => 'bg-yellow-100 text-yellow-700',
    'closed'      => 'bg-green-100 text-green-700',
];
$labels = [
    'open'        => 'Open',
    'in_progress' => 'In Progress',
    'closed'      => 'Closed',
];
@endphp
<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $colors[$status] ?? 'bg-gray-100 text-gray-600' }}">
    {{ $labels[$status] ?? $status }}
</span>
