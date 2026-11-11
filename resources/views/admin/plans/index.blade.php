<x-admin-layout>

    {{-- Topbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="hidden sm:flex items-center gap-1.5 text-[11px] text-muted mb-1 font-normal">
                <span>{{ __('messages.dashboard') }}</span>
                <span class="text-line">/</span>
                <span class="text-orange font-semibold">{{ __('messages.subscription_plans') }}</span>
            </div>
        </div>
        <div class="flex items-center gap-2 sm:gap-3.5">
            <x-language-switcher />
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

    {{-- React Mount — PlanManager.jsx كيرسم العنوان + الجدول + المودالات بلوجيك ديالو --}}
    <div id="plan-manager" data-plans="{{ json_encode($plans) }}"></div>

</x-admin-layout>
