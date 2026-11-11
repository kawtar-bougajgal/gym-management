<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<aside class="sidebar">
    <div class="sb-logo flex items-center justify-between">
        <div>
            <div class="sb-logo-text">Xtrem<em>Gym</em></div>
            <div class="sb-logo-sub">{{ __('messages.coach_panel') }}</div>
        </div>
        <button
            onclick="document.getElementById('coach-sidebar-mobile').classList.add('-translate-x-full'); document.getElementById('coach-sidebar-overlay').classList.add('hidden');"
            class="lg:hidden w-8 h-8 rounded-lg border border-line bg-page flex items-center justify-center text-text flex-shrink-0">
            <i class="ti ti-x text-[16px]"></i>
        </button>
    </div>

    <div class="sb-section">{{ __('messages.main_section') }}</div>
    <a href="{{ route('coach.dashboard') }}"
        class="sb-link {{ request()->routeIs('coach.dashboard') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <rect x="3" y="3" width="7" height="7" rx="1.5" />
            <rect x="14" y="3" width="7" height="7" rx="1.5" />
            <rect x="3" y="14" width="7" height="7" rx="1.5" />
            <rect x="14" y="14" width="7" height="7" rx="1.5" />
        </svg>
        {{ __('messages.dashboard') }}
    </a>

    <div class="sb-section">{{ __('messages.my_work_section') }}</div>
    <a href="{{ route('coach.courses') }}" class="sb-link {{ request()->routeIs('coach.courses') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M12 2L2 7l10 5 10-5-10-5z" />
            <path d="M2 17l10 5 10-5" />
            <path d="M2 12l10 5 10-5" />
        </svg>
        {{ __('messages.my_courses') }}
    </a>
    <a href="{{ route('coach.members') }}" class="sb-link {{ request()->routeIs('coach.members') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
        </svg>
        {{ __('messages.my_members') }}
    </a>

    <div class="sb-section">{{ __('messages.account_section') }}</div>
    <a href="{{ route('profile.edit') }}" class="sb-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <circle cx="12" cy="8" r="4" />
            <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
        </svg>
        {{ __('messages.my_profile') }}
    </a>

    <div class="sb-bottom">
        <a href="{{ route('logout') }}" class="sb-link"
            onclick="event.preventDefault(); document.getElementById('logout-form-c').submit();">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                <polyline points="16 17 21 12 16 7" />
                <line x1="21" y1="12" x2="9" y2="12" />
            </svg>
            {{ __('messages.logout') }}
        </a>
        <form id="logout-form-c" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>
</aside>
