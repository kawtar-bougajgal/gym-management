<x-app-layout>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <div class="grid grid-cols-1 lg:grid-cols-[230px_1fr] min-h-screen relative content-start">

        {{-- Mobile Topbar — كيبان غير lg:hidden، فيه الـ hamburger --}}
        <div class="lg:hidden flex items-center justify-between px-4 py-3 bg-surface border-b border-line sticky top-0 z-30">
            <div class="text-[18px] font-bold text-text">
                Xtrem<span class="text-orange">Gym</span>
            </div>
            <button
                onclick="document.getElementById('admin-sidebar-mobile').classList.remove('-translate-x-full'); document.getElementById('admin-sidebar-overlay').classList.remove('hidden');"
                class="w-9 h-9 rounded-lg border border-line bg-page flex items-center justify-center text-text"
            >
                <i class="ti ti-menu-2 text-[18px]"></i>
            </button>
        </div>

        {{-- Overlay — كيبان غير ملي السايدبار مفتوحة على موبايل --}}
        <div
            id="admin-sidebar-overlay"
            onclick="document.getElementById('admin-sidebar-mobile').classList.add('-translate-x-full'); this.classList.add('hidden');"
            class="hidden fixed inset-0 bg-black/50 z-40 lg:hidden"
        ></div>

        {{-- Sidebar wrapper — fixed + translate على موبايل، static على ديسكتوب --}}
        <div
            id="admin-sidebar-mobile"
            class="fixed inset-y-0 left-0 z-50 w-[230px] -translate-x-full transition-transform duration-200 lg:relative lg:translate-x-0 lg:z-auto"
        >
            <x-admin-sidebar />
        </div>

        <main class="flex flex-col gap-6 py-7 px-4 lg:px-8 bg-page overflow-y-auto">
            {{ $slot }}
        </main>

    </div>
</x-app-layout>