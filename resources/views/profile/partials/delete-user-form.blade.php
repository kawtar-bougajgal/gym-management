<section>
    <header class="mb-4">
        <h2 class="text-[15px] font-bold text-red">{{ __('messages.delete_account') }}</h2>
        <p class="text-[12.5px] text-muted mt-0.5 leading-relaxed">
            {{ __('messages.delete_account_hint') }}
        </p>
    </header>

    <button
        type="button"
        onclick="openDeleteAccountModal()"
        class="bg-red text-white py-2.5 px-5 border-none rounded-[9px] text-[13px] font-semibold cursor-pointer hover:opacity-90 transition-opacity duration-100"
    >
        {{ __('messages.delete_account') }}
    </button>

    {{-- Confirmation Modal — نفس البنية ديال #edit-member-modal (vanilla JS، بلا Alpine) --}}
    <div id="delete-account-overlay"
        onclick="closeDeleteAccountModal()"
        class="hidden fixed inset-0 bg-black/45 z-[99] backdrop-blur-sm">
    </div>

    <div id="delete-account-modal"
        class="hidden fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-surface rounded-2xl w-[440px] max-w-[92vw] z-[100] shadow-modal overflow-hidden">

        <div class="px-[22px] py-[18px] border-b border-line flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-[34px] h-[34px] rounded-[9px] bg-red-bg flex items-center justify-center">
                    <i class="ti ti-alert-triangle text-[15px] text-red"></i>
                </div>
                <div class="text-[15px] font-semibold text-text">{{ __('messages.confirm_delete_account') }}</div>
            </div>
            <button onclick="closeDeleteAccountModal()" class="w-[30px] h-[30px] rounded-lg border border-line bg-page flex items-center justify-center cursor-pointer text-muted">
                <i class="ti ti-x text-[14px]"></i>
            </button>
        </div>

        <form method="post" action="{{ route('profile.destroy') }}" class="px-[22px] py-5">
            @csrf
            @method('delete')

            <p class="text-[12.5px] text-muted leading-relaxed mb-4">
                {{ __('messages.confirm_delete_account_hint') }}
            </p>

            <label for="delete_password" class="block text-[12px] font-medium text-muted mb-[5px]">{{ __('messages.password') }} *</label>
            <input
                id="delete_password" name="password" type="password"
                placeholder="{{ __('messages.password') }}"
                class="w-full px-3 py-2.5 border {{ $errors->userDeletion->has('password') ? 'border-red' : 'border-line' }} rounded-lg text-[13.5px] outline-none bg-page text-text transition-colors duration-100 focus:border-orange focus:bg-surface"
            >
            @error('password', 'userDeletion')
                <p class="text-[11px] text-red mt-[3px]">{{ $message }}</p>
            @enderror

            <div class="flex gap-2 justify-end mt-5">
                <button type="button" onclick="closeDeleteAccountModal()" class="bg-page text-text py-2.5 px-[18px] border border-line rounded-[9px] text-[13px] font-medium cursor-pointer">
                    {{ __('messages.cancel') }}
                </button>
                <button type="submit" class="bg-red text-white py-2.5 px-5 border-none rounded-[9px] text-[13px] font-semibold cursor-pointer hover:opacity-90 transition-opacity duration-100">
                    {{ __('messages.delete_account') }}
                </button>
            </div>
        </form>
    </div>

    @if ($errors->userDeletion->isNotEmpty())
        <script>document.addEventListener('DOMContentLoaded', () => openDeleteAccountModal());</script>
    @endif

    <script>
        function openDeleteAccountModal() {
            document.getElementById('delete-account-overlay').classList.remove('hidden');
            document.getElementById('delete-account-modal').classList.remove('hidden');
        }
        function closeDeleteAccountModal() {
            document.getElementById('delete-account-overlay').classList.add('hidden');
            document.getElementById('delete-account-modal').classList.add('hidden');
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDeleteAccountModal(); });
    </script>
</section>