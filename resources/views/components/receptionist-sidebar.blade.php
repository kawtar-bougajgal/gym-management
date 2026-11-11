<aside class="sidebar">
    <div class="sb-logo flex items-center justify-between">
        <div>
            <div class="sb-logo-text">Xtrem<em>Gym</em></div>
            <div class="sb-logo-sub">{{ __('messages.receptionist_panel') }}</div>
        </div>
        <button
            onclick="document.getElementById('receptionist-sidebar-mobile').classList.add('-translate-x-full'); document.getElementById('receptionist-sidebar-overlay').classList.add('hidden');"
            class="lg:hidden w-8 h-8 rounded-lg border border-line bg-page flex items-center justify-center text-text flex-shrink-0">
            <i class="ti ti-x text-[16px]"></i>
        </button>
    </div>

    <div class="sb-section">{{ __('messages.all_statuses') }}</div>
    <a href="{{ route('receptionist.dashboard') }}"
        class="sb-link {{ request()->routeIs('receptionist.dashboard') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <rect x="3" y="3" width="7" height="7" rx="1.5" />
            <rect x="14" y="3" width="7" height="7" rx="1.5" />
            <rect x="3" y="14" width="7" height="7" rx="1.5" />
            <rect x="14" y="14" width="7" height="7" rx="1.5" />
        </svg>
        {{ __('messages.dashboard') }}
    </a>

    <div class="sb-section">{{ __('messages.members_list') }}</div>
    <a href="{{ route('members.index') }}" class="sb-link {{ request()->routeIs('members.index') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
        </svg>
        {{ __('messages.all_members') }}
    </a>

    <a href="{{ route('members.trashed') }}"
        class="sb-link {{ request()->routeIs('members.trashed') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M3 6h18" />
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
        </svg>
        {{ __('messages.trash') }}
    </a>

    {{-- Add Member — كيفتح React Modal (خدام دابا من أي صفحة، شوف التعليق تحت) --}}
    <a href="javascript:void(0)" onclick="window.dispatchEvent(new Event('open-add-member'))"
        class="sb-link {{ request()->routeIs('members.create') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M12 5v14M5 12h14" />
        </svg>
        {{ __('messages.add_member') }}
    </a>

    <div class="sb-section">{{ __('messages.subscriptions_list') }}</div>
    <a href="{{ route('subscriptions.index') }}"
        class="sb-link {{ request()->routeIs('subscriptions.index') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
        </svg>
        {{ __('messages.all_subscriptions') }}
    </a>

    {{-- New Subscription — كيفتح React Modal، بلا reload إيلا كنا ديجا فـ /subscriptions --}}
    <a href="{{ route('subscriptions.index') }}?new=1"
        onclick="if (window.location.pathname === '{{ route('subscriptions.index', [], false) }}') { event.preventDefault(); window.dispatchEvent(new Event('open-add-subscription')); }"
        class="sb-link">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M12 5v14M5 12h14" />
        </svg>
        {{ __('messages.new_subscription') }}
    </a>

    <div class="sb-section">{{ __('messages.payments_list') }}</div>
    <a href="{{ route('payments.index') }}"
        class="sb-link {{ request()->routeIs('payments.index') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
            <polyline points="14 2 14 8 20 8" />
            <line x1="16" y1="13" x2="8" y2="13" />
            <line x1="16" y1="17" x2="8" y2="17" />
        </svg>
        {{ __('messages.payment_history') }}
    </a>

    <div class="sb-section">{{ __('messages.all_courses') }}</div>
    <a href="{{ route('courses.index') }}" class="sb-link {{ request()->routeIs('courses.index') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <rect x="3" y="4" width="18" height="18" rx="2" />
            <line x1="16" y1="2" x2="16" y2="6" />
            <line x1="8" y1="2" x2="8" y2="6" />
            <line x1="3" y1="10" x2="21" y2="10" />
        </svg>
        {{ __('messages.course_schedule') }}
    </a>

    <div class="sb-section">{{ __('messages.settings') }}</div>
    <a href="{{ route('profile.edit') }}" class="sb-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <circle cx="12" cy="8" r="4" />
            <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
        </svg>
        {{ __('messages.my_profile') }}
    </a>

    <div class="sb-bottom">
        <a href="{{ route('logout') }}" class="sb-link"
            onclick="event.preventDefault(); document.getElementById('logout-form-r').submit();">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                <polyline points="16 17 21 12 16 7" />
                <line x1="21" y1="12" x2="9" y2="12" />
            </svg>
            {{ __('messages.logout') }}
        </a>
        <form id="logout-form-r" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>

    {{--
        Global "Add Member" modal mount point.
        ─────────────────────────────────────────────────────────
        السايدبار هادي موجودة فـ *كل* صفحة ديال الريسبسيونيست، إذن
        هاد الـ div كيضمن أن AddMemberModal (بلا الزر المرئي،
        showTrigger=false) راه دايما جوا الصفحة كيسمع لـ
        'open-add-member' — حتى ولا كنا فـ Subscriptions, Payments,
        Courses, Profile...

        $plans خاصها توصل هنا من مكان مركزي (View Composer) باش
        ما نضطروش نزيدو compact('plans') فـ كل controller/route.
        شوف الشرح فـ الرسالة.
    --}}
    <div id="add-member-modal-global" data-plans="{{ json_encode($plans ?? []) }}"></div>
</aside>
