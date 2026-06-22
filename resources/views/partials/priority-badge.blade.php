@php
$colors = [
    'low'    => 'bg-gray-100 text-gray-600',
    'medium' => 'bg-orange-100 text-orange-700',
    'high'   => 'bg-red-100 text-red-700',
];
@endphp
<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $colors[$priority] ?? 'bg-gray-100 text-gray-600' }}">
    {{ ucfirst($priority) }}
</span>
