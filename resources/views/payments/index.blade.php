<x-receptionist-layout>

    {{-- Topbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="hidden sm:flex items-center gap-1.5 text-[11px] text-muted mb-1 font-normal">
                <span>{{ __('messages.dashboard') }}</span>
                <span class="text-line">/</span>
                <span class="text-orange font-semibold">{{ __('messages.payment_history') }}</span>
            </div>
            <h1 class="font-bold text-[22px] tracking-[-0.4px] text-text">{{ __('messages.payment_history') }}
            </h1>
            <p class="text-[13px] text-muted mt-0.5 font-normal">
                {{ now()->locale(app()->getLocale())->isoFormat('dddd, D MMMM YYYY') }}
            </p>
        </div>
        <div class="flex items-center gap-2 sm:gap-3.5">
            <x-language-switcher />
            <div class="hidden sm:block">
                <div class="text-[13px] font-semibold text-text text-right">{{ Auth::user()->name }}</div>
                <div class="text-[11px] text-muted text-right font-normal">
                    {{ __('messages.receptionist_role') }}</div>
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
            {{ session('success') }}
        </div>
    @endif

    {{-- Stat Cards — عملية بسيطة، بلا أرقام مالية حساسة --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-stretch">

        <x-gym.stat-card value-color="orange" :label="__('messages.payments_today')" :value="$stats['today_count']" :foot="__('messages.transactions_count')">
            <x-slot:icon>
                <div
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-[15px] bg-orange-light text-orange">
                    <i class="ti ti-credit-card"></i>
                </div>
            </x-slot:icon>
        </x-gym.stat-card>

        <x-gym.stat-card value-color="blue" :label="__('messages.total_transactions')" :value="$stats['this_month_count']" :foot="__('messages.this_month')">
            <x-slot:icon>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[15px] bg-blue-bg text-blue">
                    <i class="ti ti-list-check"></i>
                </div>
            </x-slot:icon>
        </x-gym.stat-card>
    </div>

    {{-- React Mount --}}
    <div id="payment-history"
        data-payments="{{ json_encode(
            $payments->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'member' => $p->member->name ?? 'Deleted Member',
                        'member_id' => $p->member_id,
                        'member_deleted' => $p->member && $p->member->trashed(),
                        'plan' => $p->subscription->plan->name ?? '—',
                        'amount' => $p->amount,
                        'inscription_fee' => $p->inscription_fee,
                        'fee_waived' => (bool) $p->fee_waived,
                        'waived_reason' => $p->waived_reason,
                        'method' => $p->method,
                        'paid_at' => $p->paid_at,
                        'notes' => $p->notes ?? '',
                    ];
                })->values(),
        ) }}">
    </div>

</x-receptionist-layout>
