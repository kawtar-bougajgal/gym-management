@props([
    'color'      => null, // orange|green|blue|yellow|red|purple|null → لون border-top (3px). null = شفاف
    'valueColor' => null, // orange|green|blue|yellow|red|purple|null → لون الرقم. null = text-text (أسود عادي)
    'label' => '',
    'value' => '',
    'foot'  => null,
    'trend' => null, // int|null → نسبة التغيير %. موجب = أخضر، سالب = أحمر. null = ما تبانش
])

@php
    // خاصنا نكتبو الـ classes كاملين statiques (ماشي concaténation) باش Tailwind JIT يقدر يلقاهم فالملف
    $borderMap = [
        'orange' => 'border-t-orange',
        'green'  => 'border-t-green',
        'blue'   => 'border-t-blue',
        'yellow' => 'border-t-yellow',
        'red'    => 'border-t-red',
        'purple' => 'border-t-purple',
    ];
    $textMap = [
        'orange' => 'text-orange',
        'green'  => 'text-green',
        'blue'   => 'text-blue',
        'yellow' => 'text-yellow',
        'red'    => 'text-red',
        'purple' => 'text-purple',
    ];

    $borderClass     = $borderMap[$color] ?? 'border-t-transparent';
    $valueColorClass = $textMap[$valueColor] ?? 'text-text';

    $hasTrend    = !is_null($trend);
    $isPositive  = $hasTrend && $trend >= 0;
@endphp

<div {{ $attributes->merge([
    'class' => "flex flex-col gap-2.5 bg-surface border border-line {$borderClass} border-t-[3px] rounded-14 px-5 py-[18px] shadow-card min-h-[116px]"
]) }}>

    {{-- Top row: label + optional icon/action slot --}}
    <div class="flex items-center justify-between">
        <span class="text-xs font-medium text-muted">{{ $label }}</span>
        @isset($icon)
            {{ $icon }}
        @endisset
    </div>

    {{-- Value --}}
    <div class="text-[28px] font-bold leading-none font-mono {{ $valueColorClass }}">
        {{ $value }}
    </div>

    {{-- Footer: foot text + optional trend badge --}}
    @if ($foot || $hasTrend)
        <div class="flex items-center justify-between gap-2">
            @if ($foot)
                <div class="text-[11.5px] font-normal text-muted">
                    {{ $foot }}
                </div>
            @endif

            @if ($hasTrend)
                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $isPositive ? 'bg-green-bg text-green' : 'bg-red-bg text-red' }}">
                    {{ $isPositive ? '+' : '' }}{{ $trend }}%
                </span>
            @endif
        </div>
    @endif
</div>