<x-receptionist-layout>

    {{-- Topbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="hidden sm:flex items-center gap-1.5 text-[11px] text-muted mb-1 font-normal">
                <span>{{ __('messages.dashboard') }}</span>
                <span class="text-line">/</span>
                <span class="text-orange font-semibold">{{ __('messages.all_members') }}</span>
            </div>
            <h1 class="font-bold text-[22px] tracking-[-0.4px] text-text">{{ __('messages.all_members') }}</h1>
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

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-stretch">
        @php
            $total = $members->count();
            $active = $members->filter(fn($m) => $m->status === 'active')->count();
            $expiring = $members->filter(fn($m) => $m->status === 'expiring')->count();
            $expired = $members->filter(fn($m) => $m->status === 'expired')->count();
        @endphp

        <x-gym.stat-card :label="__('messages.total_members')" :value="$total" :foot="__('messages.enrolled_members')">
            <x-slot:icon>
                <div
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-[15px] bg-orange-light text-orange">
                    <i class="ti ti-users"></i>
                </div>
            </x-slot:icon>
        </x-gym.stat-card>

        <x-gym.stat-card value-color="green" :label="__('messages.active')" :value="$active" :foot="__('messages.all_active')">
            <x-slot:icon>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[15px] bg-green-bg text-green">
                    <i class="ti ti-check"></i>
                </div>
            </x-slot:icon>
        </x-gym.stat-card>

        <x-gym.stat-card value-color="yellow" :label="__('messages.expiring')" :value="$expiring" :foot="__('messages.within_7_days')">
            <x-slot:icon>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[15px] bg-yellow-bg text-yellow">
                    <i class="ti ti-alert-circle"></i>
                </div>
            </x-slot:icon>
        </x-gym.stat-card>

        <x-gym.stat-card value-color="red" :label="__('messages.expired')" :value="$expired" :foot="__('messages.renew_subscription')">
            <x-slot:icon>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[15px] bg-red-bg text-red">
                    <i class="ti ti-x"></i>
                </div>
            </x-slot:icon>
        </x-gym.stat-card>
    </div>

    {{-- Table Card --}}
    <div class="bg-surface border border-line rounded-14">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 px-5 py-4 border-b border-line">
            <div class="flex items-center gap-2.5">
                <span class="text-[15px] font-bold text-text">{{ __('messages.members_list') }}</span>
                <x-gym.badge id="count-badge" variant="neutral">
                    {{ $members->count() }} {{ __('messages.members') }}
                </x-gym.badge>
            </div>
            <div id="add-member-modal-root" data-plans="{{ json_encode($plans) }}"
                data-label="{{ __('messages.add_member_btn') }}">
            </div>
        </div>

        {{-- Search + Filter --}}
        <div class="flex items-center gap-2.5 px-5 py-3 border-b border-line">

            {{-- Search --}}
            <div class="flex-1">
                <x-gym.search-input id="search-input" placeholder="{{ __('messages.search_member') }}"
                    oninput="filterMembers()" class="w-full" />
            </div>

            {{-- Filter --}}
            <div class="relative">
                <button id="filter-btn" onclick="toggleFilter()"
                    class="flex items-center gap-1.5 px-3.5 h-11 border-[1.5px] border-line rounded-[10px] bg-surface text-[13px] text-text cursor-pointer font-medium transition-colors duration-150 hover:border-orange">
                    <i class="ti ti-filter text-[14px] text-muted"></i>
                    <span id="filter-label" class="hidden sm:inline">{{ __('messages.all_statuses') }}</span>
                    <i class="ti ti-chevron-down text-[12px] text-muted"></i>
                </button>
                <div id="filter-dropdown"
                    class="hidden absolute top-[calc(100%+6px)] right-0 bg-surface border border-line rounded-xl p-1.5 z-[200] min-w-[180px] shadow-dropdown">
                    <div class="filter-opt flex items-center gap-1.5 px-2.5 py-2 rounded-lg text-[13px] cursor-pointer transition-colors duration-100 hover:bg-orange-light hover:text-orange bg-orange-light text-orange font-semibold"
                        onclick="setFilter('all','{{ __('messages.all_statuses') }}',this)">
                        <i class="ti ti-layout-list text-[13px] text-muted"></i>{{ __('messages.all_statuses') }}
                    </div>
                    <div class="filter-opt flex items-center gap-1.5 px-2.5 py-2 rounded-lg text-[13px] text-text cursor-pointer transition-colors duration-100 hover:bg-orange-light hover:text-orange"
                        onclick="setFilter('active','{{ __('messages.active') }}',this)">
                        <i class="ti ti-circle-filled text-[8px] text-green"></i>{{ __('messages.active') }}
                    </div>
                    <div class="filter-opt flex items-center gap-1.5 px-2.5 py-2 rounded-lg text-[13px] text-text cursor-pointer transition-colors duration-100 hover:bg-orange-light hover:text-orange"
                        onclick="setFilter('expiring','{{ __('messages.expiring') }}',this)">
                        <i class="ti ti-circle-filled text-[8px] text-yellow"></i>{{ __('messages.expiring') }}
                    </div>
                    <div class="filter-opt flex items-center gap-1.5 px-2.5 py-2 rounded-lg text-[13px] text-text cursor-pointer transition-colors duration-100 hover:bg-orange-light hover:text-orange"
                        onclick="setFilter('expired','{{ __('messages.expired') }}',this)">
                        <i class="ti ti-circle-filled text-[8px] text-red"></i>{{ __('messages.expired') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Desktop Table --}}
        <table class="hidden lg:table w-full border-collapse">
            <thead>
                <tr>
                    <th
                        class="px-[18px] py-2.5 text-start text-[11px] font-semibold text-muted uppercase tracking-[0.8px] bg-page border-b border-line">
                        {{ __('messages.member') }}</th>
                    <th
                        class="px-[18px] py-2.5 text-start text-[11px] font-semibold text-muted uppercase tracking-[0.8px] bg-page border-b border-line">
                        {{ __('messages.contact') }}</th>
                    <th
                        class="px-[18px] py-2.5 text-start text-[11px] font-semibold text-muted uppercase tracking-[0.8px] bg-page border-b border-line">
                        {{ __('messages.subscription') }}</th>
                    <th
                        class="px-[18px] py-2.5 text-start text-[11px] font-semibold text-muted uppercase tracking-[0.8px] bg-page border-b border-line">
                        {{ __('messages.expiration') }}</th>
                    <th
                        class="px-[18px] py-2.5 text-start text-[11px] font-semibold text-muted uppercase tracking-[0.8px] bg-page border-b border-line">
                        {{ __('messages.status') }}</th>
                    <th
                        class="px-[18px] py-2.5 text-start text-[11px] font-semibold text-muted uppercase tracking-[0.8px] bg-page border-b border-line">
                        {{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody id="members-tbody">
                @forelse($members as $member)
                    @php
                        $sub = $member->latestSubscription;
                        $status = $member->status;
                        $avClass = match ($status) {
                            'active' => 'bg-orange-light text-orange',
                            'expiring' => 'bg-yellow-bg text-yellow',
                            'expired' => 'bg-red-bg text-red',
                            default => 'bg-[#f3f4f6] text-[#6b7280]',
                        };
                    @endphp
                    <tr class="member-row border-b border-[#f3f2ef] last:border-b-0 hover:bg-[#fafaf8] transition-colors duration-100"
                        data-name="{{ strtolower($member->name) }}"
                        data-email="{{ strtolower($member->email ?? '') }}" data-status="{{ $status }}"
                        data-id="m{{ str_pad($member->id, 3, '0', STR_PAD_LEFT) }}">

                        <td class="px-[18px] py-[13px] align-middle">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-[11px] font-semibold flex-shrink-0 font-mono uppercase {{ $avClass }}">
                                    {{ strtoupper(substr($member->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-[13px] font-medium text-text">{{ $member->name }}</div>
                                    <div class="text-[10.5px] text-muted">ID:
                                        M{{ str_pad($member->id, 3, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-[18px] py-[13px] align-middle">
                            <div class="text-[12px] text-muted leading-[1.7]">
                                @if ($member->email)
                                    <div><i class="ti ti-mail text-[11px] mr-[3px]"></i>{{ $member->email }}</div>
                                @endif
                                <div><i class="ti ti-phone text-[11px] mr-[3px]"></i>{{ $member->phone }}</div>
                            </div>
                        </td>

                        <td class="px-[18px] py-[13px] align-middle">
                            <span class="text-[13px] font-medium text-text">
                                {{ $sub?->plan?->name ?? '—' }}
                            </span>
                        </td>

                        <td class="px-[18px] py-[13px] align-middle">
                            @if ($sub)
                                <div class="text-[12px] text-muted">
                                    <i class="ti ti-calendar text-[12px] mr-[3px]"></i>
                                    {{ \Carbon\Carbon::parse($sub->end_date)->locale(app()->getLocale())->isoFormat('D MMM YYYY') }}
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td class="px-[18px] py-[13px] align-middle">
                            @if ($status === 'active')
                                <x-gym.badge variant="success">
                                    <i class="ti ti-circle-filled text-[7px]"></i>{{ __('messages.active') }}
                                </x-gym.badge>
                            @elseif($status === 'expiring')
                                <x-gym.badge variant="warning">
                                    <i class="ti ti-circle-filled text-[7px]"></i>{{ __('messages.expiring') }}
                                </x-gym.badge>
                            @elseif($status === 'expired')
                                <x-gym.badge variant="danger">
                                    <i class="ti ti-circle-filled text-[7px]"></i>{{ __('messages.expired') }}
                                </x-gym.badge>
                            @else
                                <x-gym.badge variant="neutral">
                                    <i class="ti ti-circle-filled text-[7px]"></i>{{ __('messages.no_plan') }}
                                </x-gym.badge>
                            @endif
                        </td>

                        <td class="px-[18px] py-[13px] align-middle">
                            <div class="flex gap-1.5">
                                <x-gym.btn variant="view" :href="route('members.show', $member)" title="{{ __('messages.view_all') }}">
                                    <i class="ti ti-eye text-[13px]"></i>
                                </x-gym.btn>

                                <x-gym.btn variant="edit"
                                    onclick="openEditModal({{ $member->id }},'{{ addslashes($member->name) }}','{{ $member->email }}','{{ $member->phone }}','{{ $member->age }}')"
                                    title="{{ __('messages.edit_member') }}">
                                    <i class="ti ti-edit text-[13px]"></i>
                                </x-gym.btn>

                                <form method="POST" action="{{ route('members.destroy', $member) }}"
                                    onsubmit="return confirm('{{ __('messages.confirm_delete') }} {{ addslashes($member->name) }} ?');"
                                    class="contents">
                                    @csrf @method('DELETE')
                                    <x-gym.btn variant="del" type="submit" title="{{ __('messages.delete') }}">
                                        <i class="ti ti-trash text-[13px]"></i>
                                    </x-gym.btn>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="py-12 px-6 text-center text-muted text-[14px]">
                                <i class="ti ti-users-off text-[32px] text-line block mb-2.5"></i>
                                {{ __('messages.no_members') }} —
                                <a href="javascript:void(0)"
                                    onclick="window.dispatchEvent(new Event('open-add-member'))"
                                    class="text-orange no-underline">
                                    {{ __('messages.add_first_member') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Mobile/Tablet Cards --}}
        <div id="members-cards" class="lg:hidden flex flex-col gap-3 p-4">
            @forelse($members as $member)
                @php
                    $sub = $member->latestSubscription;
                    $status = $member->status;
                    $avClass = match ($status) {
                        'active' => 'bg-orange-light text-orange',
                        'expiring' => 'bg-yellow-bg text-yellow',
                        'expired' => 'bg-red-bg text-red',
                        default => 'bg-[#f3f4f6] text-[#6b7280]',
                    };
                @endphp
                <div class="member-row member-card border border-line rounded-14 p-4 flex flex-col gap-3 bg-page/40"
                    data-name="{{ strtolower($member->name) }}" data-email="{{ strtolower($member->email ?? '') }}"
                    data-status="{{ $status }}" data-id="m{{ str_pad($member->id, 3, '0', STR_PAD_LEFT) }}">

                    <div class="flex items-center gap-2.5">
                        <div
                            class="w-9 h-9 rounded-lg flex items-center justify-center text-[11px] font-semibold flex-shrink-0 font-mono uppercase {{ $avClass }}">
                            {{ strtoupper(substr($member->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[14px] font-medium text-text truncate">{{ $member->name }}</div>
                            <div class="text-[10.5px] text-muted">ID:
                                M{{ str_pad($member->id, 3, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        @if ($status === 'active')
                            <x-gym.badge variant="success">{{ __('messages.active') }}</x-gym.badge>
                        @elseif($status === 'expiring')
                            <x-gym.badge variant="warning">{{ __('messages.expiring') }}</x-gym.badge>
                        @elseif($status === 'expired')
                            <x-gym.badge variant="danger">{{ __('messages.expired') }}</x-gym.badge>
                        @else
                            <x-gym.badge variant="neutral">{{ __('messages.no_plan') }}</x-gym.badge>
                        @endif
                    </div>

                    <div class="text-[12px] text-muted pt-2 border-t border-line flex flex-col gap-1">
                        @if ($member->email)
                            <div><i class="ti ti-mail text-[11px] mr-[3px]"></i>{{ $member->email }}</div>
                        @endif
                        <div><i class="ti ti-phone text-[11px] mr-[3px]"></i>{{ $member->phone }}</div>
                        <div>
                            <span class="font-medium text-text">{{ $sub?->plan?->name ?? '—' }}</span>
                            @if ($sub)
                                &nbsp;·&nbsp;
                                <i class="ti ti-calendar text-[11px] mr-[2px]"></i>
                                {{ \Carbon\Carbon::parse($sub->end_date)->locale(app()->getLocale())->isoFormat('D MMM YYYY') }}
                            @endif
                        </div>
                    </div>

                    <div class="flex gap-1.5 pt-1">
                        <x-gym.btn variant="view" :href="route('members.show', $member)" title="{{ __('messages.view_all') }}"
                            class="flex-1 flex items-center justify-center">
                            <i class="ti ti-eye text-[13px]"></i>
                        </x-gym.btn>
                        <x-gym.btn variant="edit"
                            onclick="openEditModal({{ $member->id }},'{{ addslashes($member->name) }}','{{ $member->email }}','{{ $member->phone }}','{{ $member->age }}')"
                            title="{{ __('messages.edit_member') }}" class="flex-1 flex items-center justify-center">
                            <i class="ti ti-edit text-[13px]"></i>
                        </x-gym.btn>
                        <form method="POST" action="{{ route('members.destroy', $member) }}"
                            onsubmit="return confirm('{{ __('messages.confirm_delete') }} {{ addslashes($member->name) }} ?');"
                            class="flex-1">
                            @csrf @method('DELETE')
                            <x-gym.btn variant="del" type="submit" title="{{ __('messages.delete') }}"
                                class="w-full flex items-center justify-center">
                                <i class="ti ti-trash text-[13px]"></i>
                            </x-gym.btn>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-12 px-6 text-center text-muted text-[14px]">
                    <i class="ti ti-users-off text-[32px] text-line block mb-2.5"></i>
                    {{ __('messages.no_members') }} —
                    <a href="javascript:void(0)" onclick="window.dispatchEvent(new Event('open-add-member'))"
                        class="text-orange no-underline">
                        {{ __('messages.add_first_member') }}
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    {{-- EDIT MEMBER MODAL --}}
    <div id="modal-overlay" onclick="closeEditModal()"
        class="hidden fixed inset-0 bg-black/45 z-[99] backdrop-blur-sm">
    </div>

    <div id="edit-member-modal"
        class="hidden fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-surface rounded-2xl p-7 w-[500px] max-w-[92vw] z-[100] shadow-modal">
        <div class="flex items-center justify-between mb-[18px]">
            <div>
                <h2 class="text-[16px] font-semibold text-text">{{ __('messages.edit_member') }}</h2>
                <p class="text-[12px] text-muted mt-0.5">{{ __('messages.update_info') }}</p>
            </div>
            <button onclick="closeEditModal()"
                class="bg-transparent border-none cursor-pointer text-muted text-[20px] leading-none p-0">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <form id="edit-member-form" method="POST" action="">
            @csrf @method('PATCH')
            <div class="mb-[13px]">
                <label class="block text-[12px] font-medium text-muted mb-[5px]">{{ __('messages.name') }} *</label>
                <input type="text" id="edit-name" name="name"
                    class="w-full px-3 py-2.5 border border-line rounded-lg text-[13.5px] outline-none bg-page text-text transition-colors duration-100 focus:border-orange focus:bg-surface"
                    placeholder="Ahmed Bennani">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 mb-[13px]">
                <div>
                    <label
                        class="block text-[12px] font-medium text-muted mb-[5px]">{{ __('messages.email') }}</label>
                    <input type="email" id="edit-email" name="email"
                        class="w-full px-3 py-2.5 border border-line rounded-lg text-[13.5px] outline-none bg-page text-text transition-colors duration-100 focus:border-orange focus:bg-surface">
                </div>
                <div>
                    <label class="block text-[12px] font-medium text-muted mb-[5px]">{{ __('messages.phone') }}
                        *</label>
                    <input type="text" id="edit-phone" name="phone"
                        class="w-full px-3 py-2.5 border border-line rounded-lg text-[13.5px] outline-none bg-page text-text transition-colors duration-100 focus:border-orange focus:bg-surface">
                </div>
            </div>
            <div class="mb-5">
                <label class="block text-[12px] font-medium text-muted mb-[5px]">{{ __('messages.age') }}</label>
                <input type="number" id="edit-age" name="age"
                    class="w-full px-3 py-2.5 border border-line rounded-lg text-[13.5px] outline-none bg-page text-text transition-colors duration-100 focus:border-orange focus:bg-surface">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 bg-orange text-white py-2.5 border-none rounded-[9px] text-[13px] font-semibold cursor-pointer hover:bg-[#ea6c05] transition-colors duration-100">
                    {{ __('messages.save') }}
                </button>
                <button type="button" onclick="closeEditModal()"
                    class="bg-page text-text py-2.5 px-[18px] border border-line rounded-[9px] text-[13px] font-medium cursor-pointer">
                    {{ __('messages.cancel') }}
                </button>
            </div>
        </form>
    </div>

    <script>
        let currentFilter = 'all';

        function openEditModal(id, name, email, phone, age) {
            document.getElementById('edit-member-form').action = `/members/${id}`;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-phone').value = phone;
            document.getElementById('edit-age').value = age;
            document.getElementById('modal-overlay').classList.remove('hidden');
            document.getElementById('edit-member-modal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('modal-overlay').classList.add('hidden');
            document.getElementById('edit-member-modal').classList.add('hidden');
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeEditModal();
        });

        function toggleFilter() {
            const dd = document.getElementById('filter-dropdown');
            dd.classList.toggle('hidden');
        }

        function setFilter(val, label, el) {
            currentFilter = val;
            document.getElementById('filter-label').textContent = label;
            document.querySelectorAll('.filter-opt').forEach(o => {
                o.classList.remove('bg-orange-light', 'text-orange', 'font-semibold');
                o.classList.add('text-text');
            });
            el.classList.remove('text-text');
            el.classList.add('bg-orange-light', 'text-orange', 'font-semibold');
            document.getElementById('filter-dropdown').classList.add('hidden');
            filterMembers();
        }

        document.addEventListener('click', e => {
            if (!e.target.closest('#filter-btn') && !e.target.closest('#filter-dropdown')) {
                document.getElementById('filter-dropdown').classList.add('hidden');
            }
        });

        // ✅ كيخدم على .member-row كاملين — سواء كانوا <tr> (الجدول) ولا <div> (الـ cards)
        // الجوج عندهم نفس الـ class و data-attributes، إذن نفس الفلتر كيخدم على بجوج بلا تفريق
        function filterMembers() {
            const q = document.getElementById('search-input').value.toLowerCase();
            const rows = document.querySelectorAll('.member-row');
            const visibleIds = new Set();

            rows.forEach(row => {
                const matchSearch = row.dataset.name.includes(q) || row.dataset.email.includes(q) || row.dataset.id
                    .includes(q);
                const matchFilter = currentFilter === 'all' || row.dataset.status === currentFilter;
                if (matchSearch && matchFilter) {
                    row.style.display = row.tagName === 'TR' ? 'table-row' : 'flex';
                    visibleIds.add(row.dataset.id);
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('count-badge').textContent = visibleIds.size + ' {{ __('messages.members') }}';
        }
    </script>

</x-receptionist-layout>
