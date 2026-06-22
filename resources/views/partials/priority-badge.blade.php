@php
$map = [
    'low'    => ['bg-slate-100 text-slate-600',  '↓ Low'],
    'medium' => ['bg-orange-100 text-orange-700','→ Medium'],
    'high'   => ['bg-red-100 text-red-700',      '↑ High'],
];
[$class, $label] = $map[$priority] ?? ['bg-gray-100 text-gray-600', ucfirst($priority)];
@endphp
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $class }}">
    {{ $label }}
</span>
