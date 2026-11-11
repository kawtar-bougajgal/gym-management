<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>XtremGym — {{ __('messages.login') }}</title>
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

            <div class="flex items-center justify-between mb-6">
                <div>
                    <div class="text-[22px] sm:text-[26px] font-extrabold text-text tracking-[-1px] mb-1">
                        {{ __('messages.login') }}
                    </div>
                    <p class="text-[13px] text-muted">{{ __('messages.login_subtitle') }}</p>
                </div>
                <x-language-switcher />
            </div>

            @if ($errors->any())
                <div
                    class="flex items-center gap-2 bg-red-bg border border-[#fca5a5] rounded-[10px] px-3.5 py-2.5 text-[12px] text-red mb-3.5">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

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
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="votre@email.com"
                            required autofocus
                            class="w-full py-2.5 pl-[38px] pr-10 border-[1.5px] border-line rounded-[10px] text-[13px] outline-none bg-page text-text transition-all duration-150 focus:border-orange focus:bg-surface focus:shadow-[0_0_0_3px_rgba(249,115,22,0.08)] placeholder:text-[#C4C2BC]">
                    </div>
                </div>

                <div class="mb-3.5">
                    <label
                        class="block text-[11px] font-bold text-text mb-1.5 uppercase tracking-[0.6px]">{{ __('messages.password') }}</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3 flex items-center text-[#C4C2BC] pointer-events-none z-[1]">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <rect x="3" y="11" width="18" height="11" rx="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                        </span>
                        <input type="password" name="password" id="password" placeholder="••••••••" required
                            class="w-full py-2.5 pl-[38px] pr-10 border-[1.5px] border-line rounded-[10px] text-[13px] outline-none bg-page text-text transition-all duration-150 focus:border-orange focus:bg-surface focus:shadow-[0_0_0_3px_rgba(249,115,22,0.08)] placeholder:text-[#C4C2BC]">
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 flex items-center bg-transparent border-none cursor-pointer text-[#C4C2BC] p-0 z-[1] hover:text-orange transition-colors duration-150">
                            <svg id="eye-show" width="14" height="14" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <svg id="eye-hide" width="14" height="14" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8" class="hidden">
                                <path
                                    d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                                <line x1="1" y1="1" x2="23" y2="23" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-5">
                    <label class="flex items-center gap-1.5 text-[12.5px] text-text cursor-pointer">
                        <input type="checkbox" name="remember" class="w-3.5 h-3.5 accent-orange cursor-pointer">
                        {{ __('messages.remember_me') }}
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-[12px] font-semibold text-orange no-underline hover:opacity-80">{{ __('messages.forgot_password') }}</a>
                    @endif
                </div>

                <button type="submit"
                    class="w-full py-3 rounded-[10px] bg-orange text-white text-[14px] font-bold border-none cursor-pointer flex items-center justify-center gap-2 mb-5 transition-all duration-200 hover:bg-[#ea6c05] hover:shadow-[0_8px_20px_rgba(249,115,22,0.3)]">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                        <polyline points="10 17 15 12 10 7" />
                        <line x1="15" y1="12" x2="3" y2="12" />
                    </svg>
                    {{ __('messages.sign_in') }}
                </button>

                <div class="flex items-center justify-center gap-1.5 text-[11.5px] text-[#C4C2BC]">
                    <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                    Xtrem Gym — Abattoir, Agadir
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eyeShow = document.getElementById('eye-show');
            const eyeHide = document.getElementById('eye-hide');
            if (input.type === 'password') {
                input.type = 'text';
                eyeShow.classList.add('hidden');
                eyeHide.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeShow.classList.remove('hidden');
                eyeHide.classList.add('hidden');
            }
        }
    </script>

</body>

</html>
