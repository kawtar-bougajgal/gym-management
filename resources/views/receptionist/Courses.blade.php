<x-receptionist-layout>

    {{-- Topbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="hidden sm:flex items-center gap-1.5 text-[11px] text-muted mb-1 font-normal">
                <span>{{ __('messages.dashboard') }}</span>
                <span class="text-line">/</span>
                <span class="text-orange font-semibold">{{ __('messages.course_schedule') }}</span>
            </div>
            <h1 class="font-bold text-[22px] tracking-[-0.4px] text-text">{{ __('messages.course_schedule') }}</h1>
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

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-stretch">

        <x-gym.stat-card :label="__('messages.total_courses')" :value="$courses->count()" :foot="__('messages.courses_available')">
            <x-slot:icon>
                <div
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-[15px] bg-orange-light text-orange">
                    <i class="ti ti-calendar-event"></i>
                </div>
            </x-slot:icon>
        </x-gym.stat-card>

        <x-gym.stat-card value-color="green" :label="__('messages.total_members')" :value="$courses->sum(fn($c) => $c->members->count())" :foot="__('messages.inscribed_courses')">
            <x-slot:icon>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[15px] bg-green-bg text-green">
                    <i class="ti ti-users"></i>
                </div>
            </x-slot:icon>
        </x-gym.stat-card>

        <x-gym.stat-card value-color="blue" :label="__('messages.active_staff')" :value="$courses->pluck('coach_id')->unique()->count()" :foot="__('messages.active_coaches')">
            <x-slot:icon>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[15px] bg-blue-bg text-blue">
                    <i class="ti ti-user-check"></i>
                </div>
            </x-slot:icon>
        </x-gym.stat-card>

        <x-gym.stat-card value-color="yellow" :label="__('messages.todays_classes')" :value="$courses->count()" :foot="__('messages.todays_courses')">
            <x-slot:icon>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[15px] bg-yellow-bg text-yellow">
                    <i class="ti ti-calendar"></i>
                </div>
            </x-slot:icon>
        </x-gym.stat-card>
    </div>

    {{-- Courses + Enroll --}}
    <div id="course-enroll-root"
        data-courses="{{ json_encode(
            $courses->map(
                fn($c) => [
                    'id' => $c->id,
                    'name' => $c->name,
                    'schedule' => $c->formatted_schedule,
                    'coach' => $c->coach?->user?->name ?? '—',
                    'members' => $c->members->map(
                        fn($m) => [
                            'id' => $m->id,
                            'name' => $m->name,
                            'phone' => $m->phone,
                        ],
                    ),
                ],
            ),
        ) }}"
        data-members="{{ json_encode(
            $members->map(
                fn($m) => [
                    'id' => $m->id,
                    'name' => $m->name,
                ],
            ),
        ) }}"
        data-csrf="{{ csrf_token() }}">
    </div>

</x-receptionist-layout>
