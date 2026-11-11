<x-receptionist-layout>

    {{-- Topbar --}}
    <div
        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface p-5 sm:p-6 rounded-14 shadow-card border border-line">
        <div>
            <div class="flex items-center gap-2 text-xs text-muted mb-1 font-sans">
                <a href="{{ route('members.index') }}"
                    class="hover:text-orange transition-colors">{{ __('messages.all_members') }}</a>
                <span class="text-line">/</span>
                <span class="text-orange font-semibold">{{ __('messages.trash') }}</span>
            </div>
            <h1 class="text-2xl font-bold text-text tracking-tight font-sans">{{ __('messages.deleted_members') }}</h1>
            <p class="text-sm text-muted font-mono mt-0.5">
                {{ __('messages.members_in_trash', ['count' => $trashedMembers->count()]) }}
            </p>
        </div>
        <x-gym.btn variant="ghost" size="sm" href="{{ route('members.index') }}">
            ← {{ __('messages.back_to_members') }}
        </x-gym.btn>
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

    {{-- Warning banner --}}
    <div
        class="flex items-center gap-3 bg-yellow-bg border border-yellow/20 text-yellow p-4 rounded-14 text-sm font-medium">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <span>{{ __('messages.trash_warning') }}</span>
    </div>

    {{-- Table Card --}}
    <div class="bg-surface p-6 rounded-14 border border-line shadow-card space-y-4">

        {{-- Desktop Table --}}
        <div class="hidden sm:block overflow-x-auto rounded-12 border border-line">
            <table class="w-full text-start border-collapse">
                <thead>
                    <tr
                        class="bg-page text-muted uppercase text-[11px] font-bold tracking-wider font-sans border-b border-line">
                        <th class="py-3.5 px-5 text-start">{{ __('messages.name') }}</th>
                        <th class="py-3.5 px-5 text-start">{{ __('messages.phone') }}</th>
                        <th class="py-3.5 px-5 text-start">{{ __('messages.deleted_on') }}</th>
                        <th class="py-3.5 px-5 text-end">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line text-sm">
                    @forelse($trashedMembers as $member)
                        <tr class="hover:bg-page/20 transition-colors">
                            <td class="py-4 px-5 font-semibold text-text font-sans">
                                {{ $member->name }}
                            </td>
                            <td class="py-4 px-5 text-muted font-mono">
                                {{ $member->phone }}
                            </td>
                            <td class="py-4 px-5 text-muted font-mono">
                                {{ \Carbon\Carbon::parse($member->deleted_at)->locale(app()->getLocale())->isoFormat('D MMM YYYY — HH:mm') }}
                            </td>
                            <td class="py-4 px-5">
                                <div class="flex justify-end gap-2">
                                    {{-- Restore --}}
                                    <form action="{{ route('members.restore', $member->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-green-bg text-green hover:bg-green/20 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            {{ __('messages.restore') }}
                                        </button>
                                    </form>

                                    {{-- Force Delete --}}
                                    <form action="{{ route('members.forceDelete', $member->id) }}" method="POST"
                                        onsubmit="return confirm('{{ addslashes(__('messages.confirm_force_delete', ['name' => $member->name])) }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-red-bg text-red hover:bg-red/20 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            {{ __('messages.delete_forever') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-muted font-sans font-medium bg-page/10">
                                {{ __('messages.trash_empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="sm:hidden flex flex-col gap-2.5">
            @forelse($trashedMembers as $member)
                <div class="border border-line rounded-12 p-4 flex flex-col gap-2.5">
                    <div>
                        <div class="font-semibold text-text font-sans">{{ $member->name }}</div>
                        <div class="text-muted font-mono text-sm">{{ $member->phone }}</div>
                        <div class="text-muted font-mono text-xs mt-1">
                            {{ __('messages.deleted_on') }}:
                            {{ \Carbon\Carbon::parse($member->deleted_at)->locale(app()->getLocale())->isoFormat('D MMM YYYY — HH:mm') }}
                        </div>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <form action="{{ route('members.restore', $member->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold bg-green-bg text-green">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                {{ __('messages.restore') }}
                            </button>
                        </form>
                        <form action="{{ route('members.forceDelete', $member->id) }}" method="POST"
                            onsubmit="return confirm('{{ addslashes(__('messages.confirm_force_delete', ['name' => $member->name])) }}');"
                            class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold bg-red-bg text-red">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                {{ __('messages.delete_forever') }}
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center text-muted font-sans font-medium bg-page/10 rounded-12">
                    {{ __('messages.trash_empty') }}
                </div>
            @endforelse
        </div>

    </div>

</x-receptionist-layout>
