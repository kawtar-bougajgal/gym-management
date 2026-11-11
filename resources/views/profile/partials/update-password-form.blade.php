<section>
    <header class="mb-5">
        <h2 class="text-[15px] font-bold text-text">{{ __('messages.update_password') }}</h2>
        <p class="text-[12.5px] text-muted mt-0.5">{{ __('messages.update_password_hint') }}</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="flex flex-col gap-3.5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password"
                class="block text-[12px] font-medium text-muted mb-[5px]">{{ __('messages.current_password') }}</label>
            <div class="relative">
                <input id="update_password_current_password" name="current_password" type="password"
                    autocomplete="current-password"
                    class="w-full px-3 py-2.5 pr-10 border {{ $errors->updatePassword->has('current_password') ? 'border-red' : 'border-line' }} rounded-lg text-[13.5px] outline-none bg-page text-text transition-colors duration-100 focus:border-orange focus:bg-surface">
                <button type="button" onclick="togglePasswordField('update_password_current_password', this)"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-muted cursor-pointer bg-transparent border-none p-0"
                    tabindex="-1">
                    <i class="ti ti-eye text-[15px]"></i>
                </button>
            </div>
            @error('current_password', 'updatePassword')
                <p class="text-[11px] text-red mt-[3px]">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password"
                class="block text-[12px] font-medium text-muted mb-[5px]">{{ __('messages.new_password') }}</label>
            <div class="relative">
                <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                    class="w-full px-3 py-2.5 pr-10 border {{ $errors->updatePassword->has('password') ? 'border-red' : 'border-line' }} rounded-lg text-[13.5px] outline-none bg-page text-text transition-colors duration-100 focus:border-orange focus:bg-surface">
                <button type="button" onclick="togglePasswordField('update_password_password', this)"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-muted cursor-pointer bg-transparent border-none p-0"
                    tabindex="-1">
                    <i class="ti ti-eye text-[15px]"></i>
                </button>
            </div>
            @error('password', 'updatePassword')
                <p class="text-[11px] text-red mt-[3px]">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation"
                class="block text-[12px] font-medium text-muted mb-[5px]">{{ __('messages.confirm_password') }}</label>
            <div class="relative">
                <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                    autocomplete="new-password"
                    class="w-full px-3 py-2.5 pr-10 border {{ $errors->updatePassword->has('password_confirmation') ? 'border-red' : 'border-line' }} rounded-lg text-[13.5px] outline-none bg-page text-text transition-colors duration-100 focus:border-orange focus:bg-surface">
                <button type="button" onclick="togglePasswordField('update_password_password_confirmation', this)"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-muted cursor-pointer bg-transparent border-none p-0"
                    tabindex="-1">
                    <i class="ti ti-eye text-[15px]"></i>
                </button>
            </div>
            @error('password_confirmation', 'updatePassword')
                <p class="text-[11px] text-red mt-[3px]">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-3 mt-1">
            <button type="submit"
                class="bg-orange text-white py-2.5 px-5 border-none rounded-[9px] text-[13px] font-semibold cursor-pointer hover:bg-[#ea6c05] transition-colors duration-100">
                {{ __('messages.save') }}
            </button>

            @if (session('status') === 'password-updated')
                <span class="text-[12.5px] text-green font-medium">{{ __('messages.saved') }}</span>
            @endif
        </div>
    </form>
</section>

<script>
    function togglePasswordField(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('ti-eye');
            icon.classList.add('ti-eye-off');
        } else {
            input.type = 'password';
            icon.classList.remove('ti-eye-off');
            icon.classList.add('ti-eye');
        }
    }
</script>
