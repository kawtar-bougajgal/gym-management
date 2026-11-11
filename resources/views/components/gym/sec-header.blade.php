@props(['title' => ''])

<div class="flex items-center justify-between mb-3.5">
    <span class="text-[15px] font-bold text-text">{{ $title }}</span>

    @isset($action)
        {{ $action }}
    @endisset
</div>