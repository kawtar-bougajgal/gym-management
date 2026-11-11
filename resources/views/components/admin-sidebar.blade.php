<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<aside class="sidebar" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

    <div class="sb-logo flex items-center justify-between">
        <div>
            <div class="sb-logo-text">Xtrem<em>Gym</em></div>
            <div class="sb-logo-sub">{{ __('messages.admin_panel') }}</div>
        </div>
        <button
            onclick="document.getElementById('admin-sidebar-mobile').classList.add('-translate-x-full'); document.getElementById('admin-sidebar-overlay').classList.add('hidden');"
            class="lg:hidden w-8 h-8 rounded-lg border border-line bg-page flex items-center justify-center text-text flex-shrink-0">
            <i class="ti ti-x text-[16px]"></i>
        </button>
    </div>

    <div class="sb-section">{{ __('messages.main_section') }}</div>
    <a href="{{ route('admin.dashboard') }}"
        class="sb-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <rect x="3" y="3" width="7" height="7" rx="1.5" />
            <rect x="14" y="3" width="7" height="7" rx="1.5" />
            <rect x="3" y="14" width="7" height="7" rx="1.5" />
            <rect x="14" y="14" width="7" height="7" rx="1.5" />
        </svg>
        {{ __('messages.dashboard') }}
    </a>

    <div class="sb-section">{{ __('messages.manage_section') }}</div>
    <a href="{{ route('admin.plans.index') }}"
        class="sb-link {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
            <rect x="9" y="3" width="6" height="4" rx="1" />
            <path d="M9 12h6M9 16h4" />
        </svg>
        {{ __('messages.subscription_plans') }}
    </a>
    <a href="{{ route('admin.coaches.index') }}"
        class="sb-link {{ request()->routeIs('admin.coaches.*') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
        </svg>
        {{ __('messages.coaches') }}
    </a>
    <a href="{{ route('admin.courses.index') }}"
        class="sb-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M12 2L2 7l10 5 10-5-10-5z" />
            <path d="M2 17l10 5 10-5" />
            <path d="M2 12l10 5 10-5" />
        </svg>
        {{ __('messages.all_courses') }}
    </a>
    <a href="{{ route('admin.receptionists.index') }}"
        class="sb-link {{ request()->routeIs('admin.receptionists.*') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
        </svg>
        {{ __('messages.receptionists_nav') }}
    </a>
    <div class="sb-section">{{ __('messages.reports_section') }}</div>
    <a href="{{ route('admin.reports') }}" class="sb-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M9 19v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2z" />
            <path d="M15 13v-2a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v2" />
            <path d="M21 19v-9a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2z" />
        </svg>
        {{ __('messages.reports') }}
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
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                <polyline points="16 17 21 12 16 7" />
                <line x1="21" y1="12" x2="9" y2="12" />
            </svg>
            {{ __('messages.logout') }}
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>

</aside>
