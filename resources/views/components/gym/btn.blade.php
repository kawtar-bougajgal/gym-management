@props([
    'variant' => 'orange', // orange | green | blue | purple | ghost
    'size'    => 'md',     // sm | md | lg
    'href'    => null,
    'type'    => 'button', // used only when rendered as <button>
])

@php
    $base = 'inline-flex items-center justify-center gap-1.5 no-underline font-semibold rounded-lg whitespace-nowrap cursor-pointer border-none transition-all duration-150 hover:-translate-y-px';

    $sizes = [
        'sm' => 'px-3 py-[5px] text-[11px]',
        'md' => 'px-4 py-[9px] text-[13px]',
        'lg' => 'px-5 py-[14px] text-[13px] rounded-xl',
    ];

    $variants = [
        'orange' => 'bg-orange text-white hover:shadow-[0_4px_12px_rgba(249,115,22,0.4)]',
        'green'  => 'bg-green text-white hover:shadow-[0_4px_12px_rgba(22,163,74,0.4)]',
        'blue'   => 'bg-blue text-white hover:shadow-[0_8px_20px_rgba(59,130,246,0.3)]',
        'purple' => 'bg-purple text-white hover:shadow-[0_8px_20px_rgba(139,92,246,0.3)]',
        'ghost'  => 'bg-page text-text border border-line hover:border-[#d0cec8]',
    ];

    $classes = trim($base . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . ($variants[$variant] ?? $variants['orange']));
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif