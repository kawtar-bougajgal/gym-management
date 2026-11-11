@php
    $layoutComponent = Auth::user()->hasRole('admin')
        ? 'admin-layout'
        : (Auth::user()->hasRole('coach')
            ? 'coach-layout'
            : 'receptionist-layout');
@endphp

<x-dynamic-component :component="$layoutComponent">

    {{-- Topbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="hidden sm:flex items-center gap-1.5 text-[11px] text-muted mb-1 font-normal">
                <span>{{ __('messages.dashboard') }}</span>
                <span class="text-line">/</span>
                <span class="text-orange font-semibold">{{ __('messages.profile') }}</span>
            </div>
            <h1 class="font-bold text-[22px] tracking-[-0.4px] text-text">{{ __('messages.profile') }}</h1>
            <p class="text-[13px] text-muted mt-0.5 font-normal">
                {{ now()->locale(app()->getLocale())->isoFormat('dddd, D MMMM YYYY') }}
            </p>
        </div>
        <div class="flex items-center gap-2 sm:gap-3.5">
            <x-language-switcher />
            <div class="hidden sm:block">
                <div class="text-[13px] font-semibold text-text text-right">{{ Auth::user()->name }}</div>
                <div class="text-[11px] text-muted text-right font-normal">
                    @if (Auth::user()->hasRole('admin'))
                        {{ __('messages.admin_role') }}
                    @elseif(Auth::user()->hasRole('coach'))
                        {{ __('messages.coach_role') }}
                    @else
                        {{ __('messages.receptionist_role') }}
                    @endif
                </div>
            </div>
            <div
                class="w-9 h-9 rounded-full bg-orange-dim flex items-center justify-center text-[13px] font-semibold text-orange font-mono flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
        </div>
    </div>

    {{-- Flash (session status: profile-updated / password-updated) --}}
    @if (session('status'))
        <div
            class="flex items-center gap-2.5 rounded-[10px] px-4 py-3 text-[13px] bg-green-bg border border-[#bbf7d0] text-green">
            {{ __('messages.saved_successfully') }}
        </div>
    @endif

    <div class="flex flex-col gap-6 max-w-2xl">

        {{-- Profile Information --}}
        <div class="bg-surface border border-line rounded-14 overflow-hidden">
            <div class="px-4 sm:px-6 py-5">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Update Password --}}
        <div class="bg-surface border border-line rounded-14 overflow-hidden">
            <div class="px-4 sm:px-6 py-5">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Delete Account --}}
        <div class="bg-surface border border-[#fca5a5] rounded-14 overflow-hidden">
            <div class="px-4 sm:px-6 py-5">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>

</x-dynamic-component>
