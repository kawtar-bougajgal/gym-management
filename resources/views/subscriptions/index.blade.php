<x-receptionist-layout>

    {{-- Topbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="hidden sm:flex items-center gap-1.5 text-[11px] text-muted mb-1 font-normal">
                <span>{{ __('messages.dashboard') }}</span>
                <span class="text-line">/</span>
                <span class="text-orange font-semibold">{{ __('messages.subscriptions') }}</span>
            </div>
            <h1 class="font-bold text-[22px] tracking-[-0.4px] text-text">{{ __('messages.subscriptions') }}</h1>
            <p class="text-[13px] text-muted mt-0.5 font-normal">
                {{ now()->locale(app()->getLocale())->isoFormat('dddd, D MMMM YYYY') }}
            </p>
        </div>
        <div class="flex items-center gap-2 sm:gap-3.5">
            <x-language-switcher />
            <div class="hidden sm:block">
                <div class="text-[13px] font-semibold text-text text-right">{{ Auth::user()->name }}</div>
                <div class="text-[11px] text-muted text-right font-normal">{{ __('messages.receptionist_role') }}</div>
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

    {{-- Alert: expiring soon --}}
    @if ($stats['expiring_soon'] > 0)
        <div
            class="flex items-center gap-2.5 rounded-[10px] px-4 py-3 text-[13px] bg-yellow-bg border border-[#fde68a] text-yellow">
            <i class="ti ti-alert-triangle text-[15px] flex-shrink-0"></i>
            <span>{{ __('messages.expiring_alert', ['count' => $stats['expiring_soon']]) }}</span>
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-stretch">

        <x-gym.stat-card :label="__('messages.total')" :value="$stats['total']" :foot="__('messages.all_subscriptions')">
            <x-slot:icon>
                <div
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-[15px] bg-orange-light text-orange">
                    <i class="ti ti-check-circle"></i>
                </div>
            </x-slot:icon>
        </x-gym.stat-card>

        <x-gym.stat-card value-color="green" :label="__('messages.active')" :value="$stats['active']" :foot="__('messages.in_progress')">
            <x-slot:icon>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[15px] bg-green-bg text-green">
                    <i class="ti ti-check"></i>
                </div>
            </x-slot:icon>
        </x-gym.stat-card>

        <x-gym.stat-card value-color="yellow" :label="__('messages.expiring_soon')" :value="$stats['expiring_soon']" :foot="__('messages.within_7_days')">
            <x-slot:icon>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[15px] bg-yellow-bg text-yellow">
                    <i class="ti ti-alert-circle"></i>
                </div>
            </x-slot:icon>
        </x-gym.stat-card>

        <x-gym.stat-card value-color="red" :label="__('messages.expired')" :value="$stats['expired']" :foot="__('messages.to_renew')">
            <x-slot:icon>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[15px] bg-red-bg text-red">
                    <i class="ti ti-x"></i>
                </div>
            </x-slot:icon>
        </x-gym.stat-card>
    </div>

    {{-- React Mount --}}
    <div id="subscription-manager" data-subscriptions="{{ json_encode($subscriptions) }}"
        data-meta="{{ json_encode($meta) }}"
        data-members="{{ json_encode($members->map(fn($m) => ['id' => $m->id, 'name' => $m->name])->values()) }}"
        data-plans="{{ json_encode($plans->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'duration' => $p->duration, 'price' => $p->price, 'inscription_fee' => $p->inscription_fee])->values()) }}"
        data-store-url="{{ route('subscriptions.store') }}" data-update-url="/subscriptions/{id}"
        data-delete-url="/subscriptions/{id}" data-csrf="{{ csrf_token() }}">
    </div>

    {{-- Hidden mount — كتخلي AddMemberModal تسمع لـ event ديال sidebar بلا ما تبين زرها --}}
    <div id="add-member-modal-root" class="absolute w-0 h-0 overflow-hidden"
        data-plans="{{ json_encode($plans->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'duration' => $p->duration, 'price' => $p->price, 'inscription_fee' => $p->inscription_fee])->values()) }}">
    </div>

</x-receptionist-layout>
