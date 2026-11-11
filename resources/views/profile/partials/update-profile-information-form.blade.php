<section>
    <header class="mb-5">
        <h2 class="text-[15px] font-bold text-text">{{ __('messages.profile_information') }}</h2>
        <p class="text-[12.5px] text-muted mt-0.5">{{ __("messages.profile_information_hint") }}</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="flex flex-col gap-3.5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-[12px] font-medium text-muted mb-[5px]">{{ __('messages.name') }} *</label>
            <input
                id="name" name="name" type="text" autofocus autocomplete="name"
                value="{{ old('name', $user->name) }}"
                class="w-full px-3 py-2.5 border {{ $errors->has('name') ? 'border-red' : 'border-line' }} rounded-lg text-[13.5px] outline-none bg-page text-text transition-colors duration-100 focus:border-orange focus:bg-surface"
            >
            @error('name')
                <p class="text-[11px] text-red mt-[3px]">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-[12px] font-medium text-muted mb-[5px]">{{ __('messages.email') }} *</label>
            <input
                id="email" name="email" type="email" autocomplete="username"
                value="{{ old('email', $user->email) }}"
                class="w-full px-3 py-2.5 border {{ $errors->has('email') ? 'border-red' : 'border-line' }} rounded-lg text-[13.5px] outline-none bg-page text-text transition-colors duration-100 focus:border-orange focus:bg-surface"
            >
            @error('email')
                <p class="text-[11px] text-red mt-[3px]">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2.5 bg-yellow-bg border border-[#fde68a] rounded-lg px-3.5 py-2.5">
                    <p class="text-[12px] text-text">
                        {{ __('messages.email_unverified') }}
                        <button form="send-verification" class="underline text-orange font-medium">
                            {{ __('messages.resend_verification') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1.5 text-[12px] font-medium text-green">
                            {{ __('messages.verification_link_sent') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-3 mt-1">
            <button type="submit" class="bg-orange text-white py-2.5 px-5 border-none rounded-[9px] text-[13px] font-semibold cursor-pointer hover:bg-[#ea6c05] transition-colors duration-100">
                {{ __('messages.save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <span class="text-[12.5px] text-green font-medium">{{ __('messages.saved') }}</span>
            @endif
        </div>
    </form>
</section>