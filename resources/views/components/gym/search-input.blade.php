@props([
    'id'          => 'search-input',
    'placeholder' => 'Search...',
    'oninput'     => '',
])

<div {{ $attributes->merge(['class' => 'flex items-center gap-2.5 bg-[var(--bg)] border-[1.5px] border-[var(--border)] rounded-[10px] h-11 px-3.5 transition-all duration-150 focus-within:border-[var(--orange)] focus-within:shadow-[0_0_0_3px_rgba(249,115,22,0.12)] w-full']) }}>
    <i class="ti ti-search text-[15px] text-[var(--muted)] flex-shrink-0"></i>
    <input
        type="text"
        id="{{ $id }}"
        placeholder="{{ $placeholder }}"
        oninput="{{ $oninput }}"
        class="border-none bg-transparent outline-none text-[13px] font-normal text-[var(--text)] w-full h-full placeholder:text-[#b0aea8]"
    >
</div>