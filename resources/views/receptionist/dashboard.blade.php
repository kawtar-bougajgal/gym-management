<x-receptionist-layout>

    {{-- Topbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="hidden sm:flex items-center gap-1.5 text-[11px] text-muted mb-1 font-normal">
                <span>{{ __('messages.dashboard') }}</span>
                <span class="text-line">/</span>
                <span class="text-orange font-semibold">{{ __('messages.receptionist_role') }}</span>
            </div>
            <h1 class="font-bold text-[22px] tracking-[-0.4px] text-text">
                {{ now()->hour < 12 ? __('messages.good_morning') : (now()->hour < 18 ? __('messages.good_afternoon') : __('messages.good_evening')) }},
                {{ Auth::user()->name }}!
            </h1>
            <p class="text-[13px] text-muted mt-0.5 font-normal">
                {{ now()->locale(app()->getLocale())->isoFormat('dddd, D MMMM YYYY') }} &nbsp;·&nbsp;
                {{ now()->format('H:i') }}
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

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-stretch">
        <x-gym.stat-card color="orange" :label="__('messages.total_members')" :value="$totalMembers ?? 0" :foot="__('messages.enrolled_members')" />
        <x-gym.stat-card color="green" value-color="green" :label="__('messages.payments_today')" :value="number_format($paymentsToday ?? 0, 2)" foot="MAD" />
        <x-gym.stat-card color="blue" value-color="blue" :label="__('messages.new_members')" :value="$newThisMonth ?? 0" :foot="__('messages.this_month')" />
        <x-gym.stat-card color="yellow" value-color="yellow" :label="__('messages.expiring_soon')" :value="$expiringSoon ?? 0" :foot="__('messages.within_7_days')" />
    </div>

    {{-- Quick Check-in --}}
    <div class="bg-surface border border-line rounded-2xl p-4 sm:p-6">
        <div class="text-[15px] font-bold text-text mb-4">
            {{ __('messages.checkin_rapide') }}
        </div>

        <div class="flex flex-col sm:flex-row gap-2.5 sm:items-center">
            <div class="flex-1 relative">
                <x-gym.search-input id="search-input" placeholder="{{ __('messages.search_member') }}"
                    oninput="filterMembers()" />

                {{-- Search Results --}}
                <div id="search-results"
                    class="hidden absolute top-[calc(100%+6px)] left-0 right-0 bg-surface border border-line rounded-xl p-2 shadow-dropdown max-h-60 overflow-y-auto z-30">
                </div>
            </div>

            <div id="add-member-modal-root" class="flex-shrink-0" data-plans="{{ json_encode($plans) }}"
                data-label="{{ __('messages.add_member_btn') }}">
            </div>
        </div>

        {{-- Recent Members --}}
        <div class="text-[11px] font-bold text-muted uppercase tracking-[0.8px] mt-5 mb-2.5">
            {{ __('messages.recent_members') }}
        </div>
        <div class="flex flex-col">
            @forelse($members->take(5) as $member)
                @php $status = $member->status; @endphp
                <a href="{{ route('members.show', $member) }}"
                    class="flex items-center gap-3 px-2.5 py-[11px] no-underline border-b border-page rounded-lg transition-colors duration-150 hover:bg-orange/[6%]">
                    <div
                        class="w-9 h-9 rounded-[10px] bg-orange-light flex items-center justify-center text-xs font-bold text-orange flex-shrink-0">
                        {{ strtoupper(substr($member->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-[13.5px] font-semibold text-text truncate">{{ $member->name }}</div>
                        <div class="text-[11px] font-normal text-muted truncate">{{ $member->phone }}</div>
                    </div>
                    @php
                        $badgeVariant =
                            $status === 'active' ? 'success' : ($status === 'expiring' ? 'warning' : 'danger');
                    @endphp
                    <x-gym.badge :variant="$badgeVariant">
                        {{ $status === 'active' ? __('messages.active') : ($status === 'expiring' ? __('messages.expiring') : ($status === 'expired' ? __('messages.expired') : __('messages.no_plan'))) }}
                    </x-gym.badge>
                </a>
            @empty
                <div class="text-center py-5 text-muted text-[13px] font-normal">
                    {{ __('messages.no_members') }}
                </div>
            @endforelse
        </div>
    </div>

    {{-- Abonnements à Traiter --}}
    <div>
        <x-gym.sec-header :title="__('messages.expiring_subscriptions') . ' / ' . __('messages.expired_subscriptions')">
            <x-slot:action>
                <x-gym.badge variant="neutral">
                    {{ $expiringSoonList->count() + $expiredList->count() }}
                </x-gym.badge>
            </x-slot:action>
        </x-gym.sec-header>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            {{-- Expiring card --}}
            <div
                class="bg-surface border border-line rounded-14 overflow-hidden shadow-[0_2px_10px_rgba(16,24,40,0.07)]">
                <div class="px-4 py-[11px] text-[11px] font-bold text-white uppercase tracking-[0.6px] bg-yellow">
                    {{ __('messages.expiring') }} ({{ $expiringSoonList->count() }})
                </div>
                @forelse($expiringSoonList as $sub)
                    @php $daysLeft = (int) now()->diffInDays(\Carbon\Carbon::parse($sub->end_date), false); @endphp
                    <div
                        class="flex items-center justify-between gap-2 px-4 py-[11px] border-b border-line transition-colors duration-150 hover:bg-orange/[5%]">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div
                                class="w-[30px] h-[30px] rounded-lg bg-yellow-bg flex items-center justify-center text-[10px] font-bold text-yellow flex-shrink-0">
                                {{ strtoupper(substr($sub->member->name ?? '?', 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-[12.5px] font-semibold text-text truncate">
                                    {{ $sub->member->name ?? '—' }}</div>
                                <div class="text-[10.5px] font-normal text-yellow">
                                    {{ $daysLeft <= 0 ? __('messages.expires_today') : $daysLeft . ' ' . __('messages.days') }}
                                </div>
                            </div>
                        </div>
                        <x-gym.btn :href="route('members.show', $sub->member_id)" variant="orange" size="sm">
                            {{ __('messages.view_label') }}
                        </x-gym.btn>
                    </div>
                @empty
                    <div class="p-6 text-center text-[12px] font-normal text-yellow">
                        {{ __('messages.tous_abonnements') }}
                    </div>
                @endforelse
            </div>

            {{-- Expired card --}}
            <div
                class="bg-surface border border-line rounded-14 overflow-hidden shadow-[0_2px_10px_rgba(16,24,40,0.07)]">
                <div class="px-4 py-[11px] text-[11px] font-bold text-white uppercase tracking-[0.6px] bg-red">
                    {{ __('messages.expired') }} ({{ $expiredList->count() }})
                </div>
                @forelse($expiredList as $sub)
                    <div
                        class="flex items-center justify-between gap-2 px-4 py-[11px] border-b border-line transition-colors duration-150 hover:bg-orange/[5%]">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div
                                class="w-[30px] h-[30px] rounded-lg bg-red-bg flex items-center justify-center text-[10px] font-bold text-red flex-shrink-0">
                                {{ strtoupper(substr($sub->member->name ?? '?', 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-[12.5px] font-semibold text-text truncate">
                                    {{ $sub->member->name ?? '—' }}</div>
                                <div class="text-[10.5px] font-normal text-red">
                                    {{ \Carbon\Carbon::parse($sub->end_date)->locale(app()->getLocale())->isoFormat('D MMM') }}
                                </div>
                            </div>
                        </div>
                        <x-gym.btn :href="route('subscriptions.index')" variant="green" size="sm">
                            {{ __('messages.renew_label') }}
                        </x-gym.btn>
                    </div>
                @empty
                    <div class="p-6 text-center text-[12px] font-normal text-muted">
                        {{ __('messages.aucun_expire') }}
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    {{-- Today's Classes --}}
    <div>
        <x-gym.sec-header :title="__('messages.todays_classes')">
            <x-slot:action>
                <a href="{{ route('courses.index') }}" class="text-[12px] text-orange no-underline font-semibold">
                    {{ __('messages.view_all') }} →
                </a>
            </x-slot:action>
        </x-gym.sec-header>

        <div class="bg-surface border border-line rounded-14 overflow-hidden">
            @forelse($courses as $course)
                @php
                    $timeLabel = '—';
                    if (!empty($course->time)) {
                        try {
                            $timeLabel = \Carbon\Carbon::parse($course->time)->format('H:i');
                        } catch (\Throwable $e) {
                            $timeLabel = $course->time;
                        }
                    }
                @endphp
                <div
                    class="flex items-center gap-[18px] px-[18px] py-[15px] border-b border-line transition-colors duration-150 hover:bg-orange/[5%]">
                    <div class="text-base font-bold text-orange min-w-[48px] text-left tracking-tight font-mono">
                        {{ $timeLabel }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-[14px] font-semibold text-text truncate">{{ $course->name }}</div>
                        <div class="text-[11.5px] font-normal text-muted mt-[3px] truncate">
                            {{ $course->coach->user->name ?? '—' }}
                            &nbsp;·&nbsp;
                            {{ $course->members_count }} {{ __('messages.enrolled_members') }}
                        </div>
                    </div>
                    <x-gym.badge variant="success-outline">
                        {{ __('messages.active') }}
                    </x-gym.badge>
                </div>
            @empty
                <div class="p-8 text-center text-[13px] font-normal text-muted">
                    {{ __('messages.no_courses') }}
                </div>
            @endforelse
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <x-gym.btn :href="route('payments.index')" variant="green" size="lg">
            {{ __('messages.payment_history') }}
        </x-gym.btn>
        <x-gym.btn :href="route('subscriptions.index')" variant="blue" size="lg">
            {{ __('messages.renew_subscription') }}
        </x-gym.btn>
        <x-gym.btn :href="route('courses.index')" variant="purple" size="lg">
            {{ __('messages.course_schedule_btn') }}
        </x-gym.btn>
    </div>

    <script>
        const members = @json($membersJson);

        const trans = {
            active: "{{ __('messages.active') }}",
            expiring: "{{ __('messages.expiring') }}",
            expired: "{{ __('messages.expired') }}",
            no_plan: "{{ __('messages.no_plan') }}",
        };

        const badgeBase =
            'inline-flex items-center gap-1 text-[10.5px] font-semibold px-2.5 py-[3px] rounded-full whitespace-nowrap';
        const badgeVariants = {
            success: 'bg-green-bg text-green',
            warning: 'bg-yellow-bg text-yellow',
            danger: 'bg-red-bg text-red',
        };

        function quickSearch(q) {
            const box = document.getElementById('search-results');
            if (!q || q.length < 2) {
                box.style.display = 'none';
                return;
            }

            const results = members.filter(m =>
                m.name.toLowerCase().includes(q.toLowerCase()) ||
                String(m.id).includes(q) ||
                `M${String(m.id).padStart(3,'0')}`.toLowerCase().includes(q.toLowerCase())
            ).slice(0, 5);

            if (!results.length) {
                box.style.display = 'none';
                return;
            }

            box.innerHTML = results.map(m => {
                const variant = m.status === 'active' ? 'success' : m.status === 'expiring' ? 'warning' : 'danger';
                const badgeLabel = m.status === 'active' ? trans.active : m.status === 'expiring' ? trans.expiring :
                    m.status === 'expired' ? trans.expired : trans.no_plan;
                return `
                <a href="${m.url}" class="flex items-center gap-2.5 px-2.5 py-[9px] rounded-lg no-underline transition-colors duration-150 hover:bg-orange/[7%]">
                    <div class="w-[30px] h-[30px] rounded-[7px] bg-orange-light flex items-center justify-center text-[10px] font-bold text-orange flex-shrink-0">
                        ${m.name.substring(0,2).toUpperCase()}
                    </div>
                    <div class="flex-1">
                        <div class="text-[13px] font-semibold text-text">${m.name}</div>
                        <div class="text-[11px] font-normal text-muted">${m.phone} · M${String(m.id).padStart(3,'0')}</div>
                    </div>
                    <span class="${badgeBase} ${badgeVariants[variant]}">${badgeLabel}</span>
                </a>
            `;
            }).join('');
            box.style.display = 'block';
        }

        document.addEventListener('click', e => {
            if (!e.target.closest('#quick-search') &&
                !e.target.closest('#search-results')) {
                document.getElementById('search-results').style.display = 'none';
            }
        });
    </script>

</x-receptionist-layout>
