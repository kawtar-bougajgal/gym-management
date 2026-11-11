<x-receptionist-layout>

    {{-- Topbar --}}
    <div
        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface p-5 sm:p-6 rounded-14 shadow-card border border-line">
        <div>
            <div class="flex items-center gap-2 text-xs text-muted mb-1 font-sans">
                <a href="{{ route('members.index') }}" class="hover:text-orange transition-colors">Members</a>
                <span class="text-line">/</span>
                <span class="text-orange font-semibold">{{ $member->name }}</span>
            </div>
            <h1 class="text-2xl font-bold text-text tracking-tight font-sans">{{ $member->name }}</h1>
            <p class="text-sm text-muted font-mono mt-0.5">Member since
                {{ $member->created_at->format('M j, Y') }}</p>
        </div>
        <div class="flex items-center gap-3 sm:gap-4">
            <div class="hidden sm:block text-right">
                <div class="text-sm font-semibold text-text font-sans">{{ Auth::user()->name }}</div>
                <div class="text-xs text-muted font-sans">Receptionist</div>
            </div>
            <div
                class="w-10 h-10 rounded-full bg-orange-dim text-orange flex items-center justify-center font-bold text-sm font-mono tracking-wider border border-orange/10 flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div
            class="flex items-center gap-3 bg-green-bg border border-green/20 text-green p-4 rounded-14 text-sm font-medium shadow-sm animate-fade-in">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Two Column Grid for Info & Subscription --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Card 1: Member Info --}}
        <div class="bg-surface p-6 rounded-14 border border-line shadow-card flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-4 mb-6">
                    <div
                        class="w-14 h-14 rounded-14 bg-orange-light flex items-center justify-center text-xl font-bold text-orange font-mono border border-orange/10">
                        {{ strtoupper(substr($member->name, 0, 2)) }}
                    </div>
                    <div class="space-y-1">
                        <div class="text-lg font-bold text-text font-sans leading-none">{{ $member->name }}
                        </div>
                        @php
                            $memberVariant = match ($member->status) {
                                'active' => 'success',
                                'expiring' => 'warning',
                                'expired' => 'danger',
                                default => 'neutral',
                            };
                        @endphp
                        <x-gym.badge :variant="$memberVariant">
                            {{ ucfirst(str_replace('_', ' ', $member->status)) }}
                        </x-gym.badge>
                    </div>
                </div>

                <hr class="border-line my-4">

                <div class="space-y-4">
                    <div class="flex justify-between items-center py-1">
                        <span class="text-xs text-muted font-bold uppercase tracking-wider font-sans">Email</span>
                        <span
                            class="text-sm font-medium text-text font-mono truncate max-w-[60%] text-right">{{ $member->email ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-xs text-muted font-bold uppercase tracking-wider font-sans">Phone</span>
                        <span
                            class="text-sm font-semibold text-text font-mono tracking-wide">{{ $member->phone }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-xs text-muted font-bold uppercase tracking-wider font-sans">Age</span>
                        <span class="text-sm font-medium text-text font-mono">{{ $member->age ?? '—' }}
                            years</span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-xs text-muted font-bold uppercase tracking-wider font-sans">Joined
                            Date</span>
                        <span
                            class="text-sm font-medium text-text font-mono">{{ $member->created_at->format('M j, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Active Subscription --}}
        <div class="bg-surface p-6 rounded-14 border border-line shadow-card flex flex-col justify-between">
            <div>
                <x-gym.sec-header title="Current Subscription" />

                @php
                    $activeSub = $member->latestSubscription;
                    $isCurrentlyActive = $activeSub && in_array($member->status, ['active', 'expiring']);
                @endphp

                @if ($activeSub && $isCurrentlyActive)
                    <div class="bg-orange-light border border-orange/10 rounded-12 p-5 mb-5 mt-4">
                        <div class="text-xl font-extrabold text-orange font-sans tracking-tight">
                            {{ $activeSub->plan->name }}
                        </div>
                        <div class="text-xs text-muted font-mono mt-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-orange/60" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $activeSub->plan->duration }} Days Plan Duration
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-0.5">
                            <span class="text-sm text-muted font-sans">Start Date</span>
                            <span
                                class="text-sm font-medium text-text font-mono">{{ \Carbon\Carbon::parse($activeSub->start_date)->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-0.5">
                            <span class="text-sm text-muted font-sans">End Date</span>
                            <span
                                class="text-sm font-medium text-text font-mono">{{ \Carbon\Carbon::parse($activeSub->end_date)->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-line">
                            <span class="text-sm font-semibold text-text font-sans">Days Remaining</span>
                            @php
                                $daysLeft = max(
                                    0,
                                    (int) now()->diffInDays(\Carbon\Carbon::parse($activeSub->end_date), false),
                                );
                                $isUrgent = $daysLeft < 7;
                            @endphp
                            <span
                                class="text-base font-bold font-mono px-2.5 py-0.5 rounded-full {{ $isUrgent ? 'bg-red/10 text-red' : 'bg-green/10 text-green' }}">
                                {{ $daysLeft }} days
                            </span>
                        </div>
                    </div>
                @elseif ($activeSub && $member->status === 'expired')
                    <div class="bg-red-bg border border-red/20 rounded-12 p-5 mb-5 mt-4">
                        <div class="text-xl font-extrabold text-red font-sans tracking-tight">
                            {{ $activeSub->plan->name }}
                        </div>
                        <div class="text-xs text-red/70 font-mono mt-1">
                            Expired on {{ \Carbon\Carbon::parse($activeSub->end_date)->format('M j, Y') }}
                        </div>
                    </div>
                    <x-gym.btn type="orange" size="sm"
                        href="{{ route('subscriptions.index') }}?new=1&member_id={{ $member->id }}">
                        Renew Subscription
                    </x-gym.btn>
                @else
                    <div
                        class="flex flex-col items-center justify-center text-center p-8 border border-dashed border-line rounded-12 bg-page/40 mt-4 space-y-4">
                        <div class="w-12 h-12 rounded-full bg-muted/10 flex items-center justify-center text-muted">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-sm text-muted font-medium font-sans">No subscription found for this
                            member.</p>
                        <x-gym.btn type="orange" size="sm"
                            href="{{ route('subscriptions.index') }}?new=1&member_id={{ $member->id }}">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Subscription
                        </x-gym.btn>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Card 3: Subscription History Table --}}
    <div class="bg-surface p-6 rounded-14 border border-line shadow-card space-y-4">
        <x-gym.sec-header title="Subscription History" />

        <div class="overflow-x-auto rounded-12 border border-line">
            <table class="w-full text-left border-collapse min-w-[500px]">
                <thead>
                    <tr
                        class="bg-page text-muted uppercase text-[11px] font-bold tracking-wider font-sans border-b border-line">
                        <th class="py-3.5 px-5">Plan</th>
                        <th class="py-3.5 px-5">Start Date</th>
                        <th class="py-3.5 px-5">End Date</th>
                        <th class="py-3.5 px-5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line text-sm">
                    @forelse($member->subscriptions->sortByDesc('end_date') as $sub)
                        @php
                            $subVariant = match ($sub->computed_status) {
                                'active' => 'success',
                                'expiring' => 'warning',
                                'expired' => 'danger',
                                default => 'neutral',
                            };
                        @endphp
                        <tr class="hover:bg-page/20 transition-colors">
                            <td class="py-4 px-5 font-semibold text-text font-sans">
                                {{ $sub->plan->name ?? '—' }}</td>
                            <td class="py-4 px-5 text-muted font-mono">
                                {{ \Carbon\Carbon::parse($sub->start_date)->format('M j, Y') }}</td>
                            <td class="py-4 px-5 text-muted font-mono">
                                {{ \Carbon\Carbon::parse($sub->end_date)->format('M j, Y') }}</td>
                            <td class="py-4 px-5">
                                <x-gym.badge :variant="$subVariant">
                                    {{ ucfirst($sub->computed_status) }}
                                </x-gym.badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-muted font-sans font-medium bg-page/10">
                                No subscription history yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Card 4: Payment History Table --}}
    <div class="bg-surface p-6 rounded-14 border border-line shadow-card space-y-4">
        <x-gym.sec-header title="Payment History" />

        <div class="overflow-x-auto rounded-12 border border-line">
            <table class="w-full text-left border-collapse min-w-[560px]">
                <thead>
                    <tr
                        class="bg-page text-muted uppercase text-[11px] font-bold tracking-wider font-sans border-b border-line">
                        <th class="py-3.5 px-5">Paid Date</th>
                        <th class="py-3.5 px-5">Membership Plan</th>
                        <th class="py-3.5 px-5">Amount Paid</th>
                        <th class="py-3.5 px-5">Payment Method</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line text-sm">
                    @forelse($member->payments as $payment)
                        <tr class="hover:bg-page/20 transition-colors">
                            <td class="py-4 px-5 text-muted font-mono">
                                {{ \Carbon\Carbon::parse($payment->paid_at)->format('M j, Y') }}
                            </td>
                            <td class="py-4 px-5 font-semibold text-text font-sans">
                                {{ $payment->subscription->plan->name ?? '—' }}
                            </td>
                            <td class="py-4 px-5">
                                <div class="flex flex-col gap-1 items-start">
                                    <span
                                        class="inline-block px-2.5 py-1 bg-orange-light text-orange rounded-md font-bold font-mono text-xs border border-orange/10">
                                        {{ number_format($payment->amount, 2) }} MAD
                                    </span>
                                    @if ($payment->fee_waived)
                                        <span title="{{ $payment->waived_reason ?? 'No reason given' }}"
                                            class="inline-flex items-center gap-1 text-[10px] font-semibold py-[2px] px-[7px] rounded-full bg-yellow-bg text-yellow border border-yellow/20 cursor-help">
                                            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                            </svg>
                                            Fee Waived
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-5 text-muted font-sans capitalize font-medium">
                                {{ $payment->method }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-muted font-sans font-medium bg-page/10">
                                No payments recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Footer Controls --}}
    <div class="flex justify-start pt-2">
        <x-gym.btn type="ghost" size="sm" href="{{ route('members.index') }}">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Members List
        </x-gym.btn>
    </div>

</x-receptionist-layout>
