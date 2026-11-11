<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>XtremGym — {{ __('messages.reset_password_page_title') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>

<body class="font-sans flex flex-col lg:flex-row h-screen overflow-hidden">

    <!-- LEFT — مخفية على موبايل/تابلت -->
    <div class="hidden lg:block lg:w-[65%] relative overflow-hidden">
        <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=1600" alt="XtremGym"
            class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 to-black/70"></div>
        <div class="absolute inset-0 flex flex-col justify-between p-10 px-12">
            <div>
                <img src="{{ asset('images/logo.png') }}" alt="XtremGym" class="w-[120px] h-auto object-contain">
            </div>
            <div>
                <div
                    class="inline-flex items-center gap-1.5 bg-white/10 border border-white/20 backdrop-blur-sm px-3.5 py-1.5 rounded-full text-[12px] text-white font-medium mb-4 w-fit">
                    <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                    Abattoir, Agadir
                </div>
                <div class="text-[40px] font-extrabold text-white leading-[1.15] tracking-[-1.5px] mb-3.5">
                    "Strength Doesn't Come<br>From What You <em class="text-orange not-italic">Can Do"</em>
                </div>
                <p class="text-[14px] text-white/65 leading-[1.7] max-w-[400px] mb-9">
                    It comes from overcoming the things you once thought you couldn't. Join Agadir's premier fitness
                    center.
                </p>
                <div class="flex gap-10">
                    <div>
                        <div class="text-[28px] font-extrabold text-white">500+</div>
                        <div class="text-[11px] text-white/50 mt-0.5">Membres Actifs</div>
                    </div>
                    <div>
                        <div class="text-[28px] font-extrabold text-white">15+</div>
                        <div class="text-[11px] text-white/50 mt-0.5">Coachs Pro</div>
                    </div>
                    <div>
                        <div class="text-[28px] font-extrabold text-white">5★</div>
                        <div class="text-[11px] text-white/50 mt-0.5">Note Moyenne</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT — كاملة على موبايل، 35% على ديسكتوب -->
    <div
        class="w-full lg:w-[35%] bg-surface flex items-center justify-center py-8 sm:py-9 px-5 sm:px-8 overflow-y-auto">
        <div class="w-full max-w-[320px]">

            <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 rounded-[12px] bg-orange-light flex items-center justify-center">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#F97316"
                        stroke-width="1.8">
                        <rect x="3" y="11" width="18" height="11" rx="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        <circle cx="12" cy="16" r="1.5" />
                    </svg>
                </div>
                <x-language-switcher />
            </div>

            <div class="text-[22px] sm:text-[26px] font-extrabold text-text tracking-[-1px] mb-1">
                {{ __('messages.reset_password_page_title') }}</div>
            <p class="text-[13px] text-muted mb-7 leading-[1.6]">
                {{ __('messages.reset_password_page_hint') }}
            </p>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="mb-3.5">
                    <label
                        class="block text-[11px] font-bold text-text mb-1.5 uppercase tracking-[0.6px]">{{ __('messages.email') }}</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3 flex items-center text-[#C4C2BC] pointer-events-none z-[1]">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}"
                            required autofocus autocomplete="username"
                            class="w-full py-2.5 pl-[38px] pr-3.5 border-[1.5px] border-line rounded-[10px] text-[13px] outline-none bg-page text-text transition-all duration-150 focus:border-orange focus:bg-surface focus:shadow-[0_0_0_3px_rgba(249,115,22,0.08)]">
                    </div>
                    @error('email')
                        <p class="text-[11px] text-red mt-[5px]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3.5">
                    <label
                        class="block text-[11px] font-bold text-text mb-1.5 uppercase tracking-[0.6px]">{{ __('messages.new_password') }}</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3 flex items-center text-[#C4C2BC] pointer-events-none z-[1]">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <rect x="3" y="11" width="18" height="11" rx="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                        </span>
                        <input type="password" id="password" name="password" required autocomplete="new-password"
                            placeholder="••••••••"
                            class="w-full py-2.5 pl-[38px] pr-10 border-[1.5px] border-line rounded-[10px] text-[13px] outline-none bg-page text-text transition-all duration-150 focus:border-orange focus:bg-surface focus:shadow-[0_0_0_3px_rgba(249,115,22,0.08)]">
                        <button type="button" onclick="togglePasswordField('password', this)"
                            class="absolute right-3 flex items-center bg-transparent border-none cursor-pointer text-[#C4C2BC] p-0 z-[1] hover:text-orange transition-colors duration-150">
                            <i class="ti ti-eye text-[15px]"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-[11px] text-red mt-[5px]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label
                        class="block text-[11px] font-bold text-text mb-1.5 uppercase tracking-[0.6px]">{{ __('messages.confirm_password') }}</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3 flex items-center text-[#C4C2BC] pointer-events-none z-[1]">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="11" width="18" height="11" rx="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                        </span>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            autocomplete="new-password" placeholder="••••••••"
                            class="w-full py-2.5 pl-[38px] pr-10 border-[1.5px] border-line rounded-[10px] text-[13px] outline-none bg-page text-text transition-all duration-150 focus:border-orange focus:bg-surface focus:shadow-[0_0_0_3px_rgba(249,115,22,0.08)]">
                        <button type="button" onclick="togglePasswordField('password_confirmation', this)"
                            class="absolute right-3 flex items-center bg-transparent border-none cursor-pointer text-[#C4C2BC] p-0 z-[1] hover:text-orange transition-colors duration-150">
                            <i class="ti ti-eye text-[15px]"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="text-[11px] text-red mt-[5px]">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full py-3 rounded-[10px] bg-orange text-white text-[14px] font-bold border-none cursor-pointer flex items-center justify-center gap-2 transition-all duration-200 hover:bg-[#ea6c05] hover:shadow-[0_8px_20px_rgba(249,115,22,0.3)]">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('messages.reset_password') }}
                </button>

            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
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

</body>

</html>
