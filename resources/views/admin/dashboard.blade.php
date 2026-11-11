<x-admin-layout>

    {{-- Topbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="hidden sm:flex items-center gap-1.5 text-[11px] text-muted mb-1 font-normal">
                <span>{{ __('messages.admin') }}</span>
                <span class="text-line">/</span>
                <span class="text-orange font-semibold">{{ __('messages.dashboard') }}</span>
            </div>
            <h1 class="font-bold text-[22px] tracking-[-0.4px] text-text">{{ __('messages.dashboard') }}</h1>
            <p class="text-[13px] text-muted mt-0.5 font-normal">
                {{ now()->locale(app()->getLocale())->isoFormat('dddd, D MMMM YYYY') }}
            </p>
        </div>
        <div class="flex items-center gap-2 sm:gap-3.5">
            <x-language-switcher />

            {{-- Search — hidden على موبايل، بغا مكان واسع بزاف --}}
            <div
                class="hidden md:flex items-center gap-2 bg-page border border-line rounded-[10px] py-2 px-3.5 w-[220px]">
                <i class="ti ti-search text-[13px] text-muted flex-shrink-0"></i>
                <input type="text" placeholder="{{ __('messages.search_placeholder') }}"
                    class="border-none bg-transparent outline-none text-[13px] text-text w-full font-normal">
            </div>

            {{-- Notifications --}}
            <div class="relative hidden sm:block">
                <div
                    class="w-9 h-9 rounded-[10px] border border-line bg-surface flex items-center justify-center text-muted">
                    <i class="ti ti-bell text-[16px]"></i>
                </div>
            </div>

            {{-- User --}}
            <div class="hidden sm:block">
                <div class="text-[13px] font-semibold text-text text-right">{{ Auth::user()->name }}</div>
                <div class="text-[11px] text-muted text-right font-normal">{{ __('messages.admin_role') }}</div>
            </div>
            <div
                class="w-9 h-9 rounded-full bg-orange-dim flex items-center justify-center text-[13px] font-semibold text-orange font-mono flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
        </div>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div
            class="flex items-center gap-2.5 rounded-[10px] px-4 py-3 text-[13px] bg-green-bg border border-[#bbf7d0] text-green">
            <i class="ti ti-check-circle text-[15px] flex-shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- React Dashboard --}}
    @php
        $dashProps = json_encode([
            'totalMembers' => $totalMembers,
            'newThisMonth' => $newThisMonth,
            'newMembersTrend' => $newMembersTrend,
            'activeStaff' => $activeStaff ?? 0,
            'monthlyMembers' => $monthlyMembers,
            'plans' => $plans->map(
                fn($p) => [
                    'name' => $p->name,
                    'subscribers' => $p->subscriptions()->count(),
                ],
            ),
        ]);
    @endphp
    <div id="admin-dashboard" data-props="{{ $dashProps }}">
    </div>

    {{-- Quick Actions --}}
    <div>
        <span
            class="text-[11px] font-semibold text-muted uppercase tracking-[0.8px]">{{ __('messages.quick_actions') }}</span>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mt-2.5">

            <a href="{{ route('admin.coaches.index') }}"
                class="flex items-center gap-3 py-4 px-[18px] bg-orange rounded-[14px] no-underline transition-colors duration-150 hover:bg-[#ea6c05]">
                <div class="w-9 h-9 rounded-[9px] bg-white/20 flex items-center justify-center flex-shrink-0">
                    <i class="ti ti-users text-[16px] text-white"></i>
                </div>
                <div>
                    <div class="text-[13.5px] font-semibold text-white">{{ __('messages.manage_coaches') }}</div>
                    <div class="text-[11px] text-white/75 mt-px">{{ __('messages.view_all_coaches') }}</div>
                </div>
            </a>

            <a href="{{ route('admin.plans.index') }}"
                class="flex items-center gap-3 py-4 px-[18px] bg-green rounded-[14px] no-underline transition-colors duration-150 hover:bg-[#15803d]">
                <div class="w-9 h-9 rounded-[9px] bg-white/20 flex items-center justify-center flex-shrink-0">
                    <i class="ti ti-clipboard-list text-[16px] text-white"></i>
                </div>
                <div>
                    <div class="text-[13.5px] font-semibold text-white">{{ __('messages.subscription_plans') }}</div>
                    <div class="text-[11px] text-white/75 mt-px">{{ __('messages.manage_plans_desc') }}</div>
                </div>
            </a>

            <a href="{{ route('admin.courses.index') }}"
                class="flex items-center gap-3 py-4 px-[18px] bg-blue rounded-[14px] no-underline transition-colors duration-150 hover:bg-[#2563eb]">
                <div class="w-9 h-9 rounded-[9px] bg-white/20 flex items-center justify-center flex-shrink-0">
                    <i class="ti ti-calendar-event text-[16px] text-white"></i>
                </div>
                <div>
                    <div class="text-[13.5px] font-semibold text-white">{{ __('messages.manage_courses') }}</div>
                    <div class="text-[11px] text-white/75 mt-px">{{ __('messages.view_all_courses') }}</div>
                </div>
            </a>

            <a href="{{ route('admin.receptionists.index') }}"
                class="flex items-center gap-3 py-4 px-[18px] bg-purple rounded-[14px] no-underline transition-colors duration-150 hover:bg-[#7c3aed]">
                <div class="w-9 h-9 rounded-[9px] bg-white/20 flex items-center justify-center flex-shrink-0">
                    <i class="ti ti-user-plus text-[16px] text-white"></i>
                </div>
                <div>
                    <div class="text-[13.5px] font-semibold text-white">{{ __('messages.receptionists_label') }}</div>
                    <div class="text-[11px] text-white/75 mt-px">{{ __('messages.manage_staff') }}</div>
                </div>
            </a>

        </div>
    </div>

</x-admin-layout>
