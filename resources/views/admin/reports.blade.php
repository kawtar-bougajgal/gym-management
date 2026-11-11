<x-admin-layout>

    {{-- Topbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="hidden sm:flex items-center gap-1.5 text-[11px] text-muted mb-1 font-normal">
                <span>{{ __('messages.admin') }}</span>
                <span class="text-line">/</span>
                <span class="text-orange font-semibold">{{ __('messages.reports') }}</span>
            </div>
            <h1 class="font-bold text-[22px] tracking-[-0.4px] text-text">{{ __('messages.reports') }}</h1>
            <p class="text-[13px] text-muted mt-0.5 font-normal">
                {{ now()->locale(app()->getLocale())->isoFormat('dddd, D MMMM YYYY') }}
            </p>
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

    {{-- 🔧 Inject translations + locale for React BEFORE the bundle loads --}}
    <script>
        window.translations = @json(trans('messages'));
        window.locale = "{{ app()->getLocale() }}";
    </script>

    <div id="admin-reports"
        data-props='{{ json_encode([
            'totalRevenue' => $totalRevenue,
            'revenueByMonth' => $revenueByMonth,
            'paymentsByMethod' => $paymentsByMethod,
            'totalMembers' => $totalMembers,
            'activeMembers' => $activeMembers,
            'expiredMembers' => $expiredMembers,
            'popularPlans' => $popularPlans,
            'waivedCount' => $waivedCount,
            'waivedAmount' => $waivedAmount,
            'waivedTrend' => $waivedTrend,
        ]) }}'>
    </div>

</x-admin-layout>
