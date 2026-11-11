@props([
    'variant' => 'neutral', // success | success-outline | warning | danger | neutral | blue
])

@php
    $variants = [
        'success'         => 'bg-green-bg text-green',
        'success-outline' => 'bg-green-bg text-green border border-green/20',
        'warning'         => 'bg-yellow-bg text-yellow',
        'danger'          => 'bg-red-bg text-red',
        'neutral'         => 'bg-page text-muted border border-line text-[11px]',
        'blue'            => 'bg-blue-bg text-blue',
    ];

    $classes = $variants[$variant] ?? $variants['neutral'];
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center gap-1 text-[10.5px] font-semibold px-2.5 py-[3px] rounded-full whitespace-nowrap {$classes}"
]) }}>
    {{ $slot }}
</span>