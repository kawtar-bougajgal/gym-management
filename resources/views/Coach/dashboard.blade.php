<x-coach-layout>

    {{-- Topbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="hidden sm:flex items-center gap-1.5 text-[11px] text-muted mb-1 font-normal">
                <span>{{ __('messages.coach_role') }}</span>
                <span class="text-line">/</span>
                <span class="text-orange font-semibold">{{ __('messages.dashboard') }}</span>
            </div>
            <h1 class="font-bold text-[22px] tracking-[-0.4px] text-text">
                {{ now()->hour < 12 ? __('messages.good_morning') : (now()->hour < 18 ? __('messages.good_afternoon') : __('messages.good_evening')) }},
                {{ Auth::user()->name }}!
            </h1>
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

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-stretch">

        <x-gym.stat-card color="orange" :label="__('messages.my_courses')" :value="$courses->count()" :foot="__('messages.courses_assigned')">
            <x-slot:icon>
                <div
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-[15px] bg-orange-light text-orange">
                    <i class="ti ti-calendar-event"></i>
                </div>
            </x-slot:icon>
        </x-gym.stat-card>

        <x-gym.stat-card color="blue" value-color="blue" :label="__('messages.total_members')" :value="$totalMembers" :foot="__('messages.enrolled_in_my_courses')">
            <x-slot:icon>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[15px] bg-blue-bg text-blue">
                    <i class="ti ti-users"></i>
                </div>
            </x-slot:icon>
        </x-gym.stat-card>

        <x-gym.stat-card color="purple" value-color="purple" :label="__('messages.specialization')" :value="$coach?->specialization ?? '—'" :foot="__('messages.your_specialty')">
            <x-slot:icon>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[15px] bg-purple-bg text-purple">
                    <i class="ti ti-award"></i>
                </div>
            </x-slot:icon>
        </x-gym.stat-card>

        <x-gym.stat-card color="yellow" value-color="yellow" :label="__('messages.next_course')" :value="$nextCourse?->name ?? '—'" :foot="$nextCourse?->schedule ?? __('messages.no_courses_assigned')">
            <x-slot:icon>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[15px] bg-yellow-bg text-yellow">
                    <i class="ti ti-clock"></i>
                </div>
            </x-slot:icon>
        </x-gym.stat-card>

    </div>

    {{-- My Courses --}}
    <div>
        <div class="flex items-center gap-2 mb-3">
            <i class="ti ti-book text-[15px] text-orange"></i>
            <span class="text-[15px] font-bold text-text">{{ __('messages.my_courses') }}</span>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden sm:block bg-surface border border-line rounded-14 overflow-hidden">
            <table class="w-full border-collapse">
                <thead>
                    <tr>
                        <th
                            class="px-[18px] py-2.5 text-start text-[11px] font-semibold text-muted uppercase tracking-[0.8px] bg-page border-b border-line">
                            {{ __('messages.course') }}</th>
                        <th
                            class="px-[18px] py-2.5 text-start text-[11px] font-semibold text-muted uppercase tracking-[0.8px] bg-page border-b border-line">
                            {{ __('messages.schedule') }}</th>
                        <th
                            class="px-[18px] py-2.5 text-start text-[11px] font-semibold text-muted uppercase tracking-[0.8px] bg-page border-b border-line">
                            {{ __('messages.enrolled_members') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr
                            class="border-b border-[#f3f2ef] last:border-b-0 hover:bg-[#fafaf8] transition-colors duration-100">
                            <td class="px-[18px] py-[13px]">
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="w-[34px] h-[34px] rounded-[9px] bg-orange-light flex items-center justify-center text-[11px] font-bold text-orange">
                                        {{ strtoupper(substr($course->name, 0, 2)) }}
                                    </div>
                                    <span class="text-[13.5px] font-medium text-text">{{ $course->name }}</span>
                                </div>
                            </td>
                            <td class="px-[18px] py-[13px] text-[13px] text-muted">{{ $course->formatted_schedule }}
                            </td>
                            <td class="px-[18px] py-[13px]">
                                <span class="text-[13px] font-semibold text-blue font-mono">
                                    {{ $course->members->count() }} {{ __('messages.members_label') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="py-12 px-6 text-center text-muted text-[14px]">
                                    <i class="ti ti-calendar-off text-[32px] text-line block mb-2.5"></i>
                                    {{ __('messages.no_courses_assigned') }}
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="sm:hidden flex flex-col gap-2.5">
            @forelse($courses as $course)
                <div class="bg-surface border border-line rounded-14 p-4 flex items-center gap-3">
                    <div
                        class="w-[38px] h-[38px] rounded-[9px] bg-orange-light flex items-center justify-center text-[12px] font-bold text-orange flex-shrink-0">
                        {{ strtoupper(substr($course->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-[14px] font-semibold text-text truncate">{{ $course->name }}</div>
                        <div class="text-[12px] text-muted mt-0.5">{{ $course->formatted_schedule }}</div>
                    </div>
                    <span class="text-[12px] font-semibold text-blue font-mono flex-shrink-0">
                        {{ $course->members->count() }} {{ __('messages.members_label') }}
                    </span>
                </div>
            @empty
                <div class="bg-surface border border-line rounded-14 py-12 px-6 text-center text-muted text-[14px]">
                    <i class="ti ti-calendar-off text-[32px] text-line block mb-2.5"></i>
                    {{ __('messages.no_courses_assigned') }}
                </div>
            @endforelse
        </div>
    </div>

    {{-- My Members --}}
    <div>
        <div class="flex items-center gap-2 mb-3">
            <i class="ti ti-users text-[15px] text-orange"></i>
            <span class="text-[15px] font-bold text-text">{{ __('messages.my_members') }}</span>
        </div>

        {{-- Desktop Table --}}
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
                    @forelse($courses as $course)
                        @foreach ($course->members as $member)
                            <tr
                                class="border-b border-[#f3f2ef] last:border-b-0 hover:bg-[#fafaf8] transition-colors duration-100">
                                <td class="px-[18px] py-[13px]">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-orange-light flex items-center justify-center text-[11px] font-semibold text-orange font-mono uppercase">
                                            {{ strtoupper(substr($member->name, 0, 2)) }}
                                        </div>
                                        <span class="text-[13px] font-medium text-text">{{ $member->name }}</span>
                                    </div>
                                </td>
                                <td class="px-[18px] py-[13px] text-[12px] text-muted">{{ $member->phone }}</td>
                                <td class="px-[18px] py-[13px] text-[12.5px] text-text">{{ $course->name }}</td>
                            </tr>
                        @endforeach
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

        {{-- Mobile Cards --}}
        <div class="sm:hidden flex flex-col gap-2.5">
            @forelse($courses as $course)
                @foreach ($course->members as $member)
                    <div class="bg-surface border border-line rounded-14 p-4 flex items-center gap-3">
                        <div
                            class="w-9 h-9 rounded-lg bg-orange-light flex items-center justify-center text-[11px] font-semibold text-orange font-mono uppercase flex-shrink-0">
                            {{ strtoupper(substr($member->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[14px] font-medium text-text truncate">{{ $member->name }}</div>
                            <div class="text-[12px] text-muted mt-0.5">{{ $member->phone }}</div>
                        </div>
                        <span class="text-[12px] text-text flex-shrink-0">{{ $course->name }}</span>
                    </div>
                @endforeach
            @empty
                <div class="bg-surface border border-line rounded-14 py-12 px-6 text-center text-muted text-[14px]">
                    <i class="ti ti-users-off text-[32px] text-line block mb-2.5"></i>
                    {{ __('messages.no_members_enrolled') }}
                </div>
            @endforelse
        </div>
    </div>

</x-coach-layout>
