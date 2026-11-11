<x-coach-layout>

    {{-- Topbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="hidden sm:flex items-center gap-1.5 text-[11px] text-muted mb-1 font-normal">
                <span>{{ __('messages.coach_role') }}</span>
                <span class="text-line">/</span>
                <span class="text-orange font-semibold">{{ __('messages.my_members') }}</span>
            </div>
            <h1 class="font-bold text-[22px] tracking-[-0.4px] text-text">{{ __('messages.my_members') }}</h1>
            <p class="text-[13px] text-muted mt-0.5 font-normal">
                {{ now()->locale(app()->getLocale())->isoFormat('dddd, D MMMM YYYY') }}
            </p>
        </div>
        <div class="flex items-center gap-2 sm:gap-3.5">
            <x-language-switcher />
            <div class="hidden sm:block">
                <div class="text-[13px] font-semibold text-text text-right">{{ Auth::user()->name }}</div>
                <div class="text-[11px] text-muted text-right font-normal">{{ __('messages.coach_role') }} —
                    {{ $coach?->specialization ?? '—' }}</div>
            </div>
            <div
                class="w-9 h-9 rounded-full bg-orange-dim flex items-center justify-center text-[13px] font-semibold text-orange font-mono flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
        </div>
    </div>

    {{-- Members — Desktop Table --}}
    <div class="hidden sm:block bg-surface border border-line rounded-14 overflow-hidden">
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th
                        class="px-[18px] py-2.5 text-start text-[11px] font-semibold text-muted uppercase tracking-[0.8px] bg-page border-b border-line">
                        {{ __('messages.member') }}</th>
                    <th
                        class="px-[18px] py-2.5 text-start text-[11px] font-semibold text-muted uppercase tracking-[0.8px] bg-page border-b border-line">
                        {{ __('messages.phone') }}</th>
                    <th
                        class="px-[18px] py-2.5 text-start text-[11px] font-semibold text-muted uppercase tracking-[0.8px] bg-page border-b border-line">
                        {{ __('messages.course') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                    <tr
                        class="border-b border-[#f3f2ef] last:border-b-0 hover:bg-[#fafaf8] transition-colors duration-100">
                        <td class="px-[18px] py-[13px]">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="w-8 h-8 rounded-lg bg-orange-light flex items-center justify-center text-[11px] font-semibold text-orange font-mono uppercase">
                                    {{ strtoupper(substr($member['name'], 0, 2)) }}
                                </div>
                                <span class="text-[13px] font-medium text-text">{{ $member['name'] }}</span>
                            </div>
                        </td>
                        <td class="px-[18px] py-[13px] text-[12px] text-muted">{{ $member['phone'] }}</td>
                        <td class="px-[18px] py-[13px] text-[12.5px] text-text">{{ $member['course'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <div class="py-12 px-6 text-center text-muted text-[14px]">
                                <i class="ti ti-users-off text-[32px] text-line block mb-2.5"></i>
                                {{ __('messages.no_members_enrolled') }}
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Members — Mobile Cards --}}
    <div class="sm:hidden flex flex-col gap-2.5">
        @forelse($members as $member)
            <div class="bg-surface border border-line rounded-14 p-4 flex items-center gap-3">
                <div
                    class="w-9 h-9 rounded-lg bg-orange-light flex items-center justify-center text-[11px] font-semibold text-orange font-mono uppercase flex-shrink-0">
                    {{ strtoupper(substr($member['name'], 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[14px] font-medium text-text truncate">{{ $member['name'] }}</div>
                    <div class="text-[12px] text-muted mt-0.5">{{ $member['phone'] }}</div>
                </div>
                <span class="text-[12px] text-text flex-shrink-0">{{ $member['course'] }}</span>
            </div>
        @empty
            <div class="bg-surface border border-line rounded-14 py-12 px-6 text-center text-muted text-[14px]">
                <i class="ti ti-users-off text-[32px] text-line block mb-2.5"></i>
                {{ __('messages.no_members_enrolled') }}
            </div>
        @endforelse
    </div>

</x-coach-layout>
