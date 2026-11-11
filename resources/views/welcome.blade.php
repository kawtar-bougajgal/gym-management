<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XtremGym — Agadir</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=DM+Mono&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>

<body class="font-sans text-text">

    <!-- NAV -->
    <nav
        class="fixed top-0 left-0 right-0 flex items-center justify-between px-5 sm:px-12 py-3.5 sm:py-4 bg-white/95 backdrop-blur-md border-b border-black/[0.06] z-[100]">
        <div class="text-[18px] sm:text-[22px] font-extrabold text-text tracking-[-0.5px]">
            Xtrem<span class="text-orange">Gym</span>
        </div>
        <a href="{{ route('login') }}"
            class="inline-flex items-center gap-1.5 px-3.5 sm:px-[18px] py-2 sm:py-2.5 rounded-[10px] bg-orange text-white text-[12px] sm:text-[13px] font-semibold no-underline hover:bg-[#ea6c05] transition-colors duration-150">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                <polyline points="10 17 15 12 10 7" />
                <line x1="15" y1="12" x2="3" y2="12" />
            </svg>
            <span class="hidden sm:inline">Connexion Staff</span>
        </a>
    </nav>

    <!-- HERO -->
    <div class="relative w-full h-screen overflow-hidden flex justify-center items-center text-center text-white">
        <video class="absolute top-0 left-0 w-full h-full object-cover z-[1]" autoplay muted loop playsinline>
            <source src="{{ asset('videos/hero.mp4') }}" type="video/mp4">
        </video>

        <div class="absolute inset-0 z-[2]"
            style="background: radial-gradient(ellipse at center, rgba(0,0,0,0.08) 0%, rgba(0,0,0,0.40) 75%), linear-gradient(to bottom, rgba(8,6,4,0.55) 0%, rgba(88,32,12,0.30) 42%, rgba(60,20,8,0.35) 58%, rgba(8,6,4,0.60) 100%);">
        </div>

        <div class="relative z-[3] flex flex-col items-center px-5">
            <div class="mb-6 sm:mb-7">
                <img src="{{ asset('images/logo.png') }}" alt="XtremGym Logo"
                    class="w-[180px] sm:w-[300px] max-w-[92%] h-auto object-contain"
                    style="filter: drop-shadow(0 10px 30px rgba(0,0,0,0.55)) drop-shadow(0 0 40px rgba(249,115,22,0.25));">
            </div>

            <p class="text-[14px] sm:text-[18px] text-white/90 px-4">Push Your Limits in Agadir's Premier Fitness Center
            </p>

            <div class="flex items-center gap-1.5 text-[12px] sm:text-[14px] text-white/70 mt-2">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                    <circle cx="12" cy="10" r="3" />
                </svg>
                Abattoir — Agadir
            </div>

            <div class="flex flex-col sm:flex-row gap-3 mt-8 w-full sm:w-auto px-8 sm:px-0">
                <a href="{{ route('login') }}"
                    class="text-center px-7 py-3.5 rounded-xl bg-orange text-white text-[14px] font-semibold no-underline hover:bg-[#ea6c05] hover:-translate-y-0.5 transition-all duration-150">
                    Commencer Maintenant
                </a>
                <a href="#services"
                    class="text-center px-7 py-3.5 rounded-xl bg-white/15 text-white text-[14px] font-semibold no-underline border-2 border-white/50 backdrop-blur-sm hover:bg-white/25 hover:-translate-y-0.5 transition-all duration-150">
                    Découvrir
                </a>
            </div>
        </div>
    </div>

    <!-- STATS -->
    <div class="bg-orange grid grid-cols-2 sm:grid-cols-4">
        <div class="py-6 sm:py-10 px-3 sm:px-10 text-center border-r border-b sm:border-b-0 border-white/20">
            <div class="text-[28px] sm:text-[48px] font-extrabold text-white font-mono">500+</div>
            <div class="text-[11px] sm:text-[13px] text-white/80 mt-1">Membres Actifs</div>
        </div>
        <div class="py-6 sm:py-10 px-3 sm:px-10 text-center sm:border-r border-b sm:border-b-0 border-white/20">
            <div class="text-[28px] sm:text-[48px] font-extrabold text-white font-mono">15+</div>
            <div class="text-[11px] sm:text-[13px] text-white/80 mt-1">Coachs Professionnels</div>
        </div>
        <div class="py-6 sm:py-10 px-3 sm:px-10 text-center border-r border-white/20">
            <div class="text-[28px] sm:text-[48px] font-extrabold text-white font-mono">20+</div>
            <div class="text-[11px] sm:text-[13px] text-white/80 mt-1">Cours Disponibles</div>
        </div>
        <div class="py-6 sm:py-10 px-3 sm:px-10 text-center">
            <div class="text-[28px] sm:text-[48px] font-extrabold text-white font-mono">5★</div>
            <div class="text-[11px] sm:text-[13px] text-white/80 mt-1">Note Moyenne</div>
        </div>
    </div>

    <!-- SERVICES -->
    <section id="services" class="bg-page py-12 sm:py-20 px-5 sm:px-12">
        <div class="mb-8 sm:mb-12">
            <div class="text-[11px] font-bold text-orange uppercase tracking-[2px] mb-3">Nos Services</div>
            <div class="text-[26px] sm:text-[36px] font-bold text-text tracking-[-1px] mb-3">Tout ce dont vous avez
                besoin</div>
            <div class="text-[14px] sm:text-[15px] text-muted max-w-[500px]">Des équipements modernes et des coachs
                certifiés pour atteindre vos objectifs</div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <div
                class="bg-surface border border-line rounded-2xl p-6 sm:p-7 transition-all duration-200 hover:-translate-y-1.5 hover:shadow-[0_16px_40px_rgba(0,0,0,0.08)] hover:border-orange">
                <div class="w-[52px] h-[52px] rounded-[14px] bg-orange-light flex items-center justify-center mb-4">
                    <img src="{{ asset('images/icons/musculation.png') }}" alt="Musculation"
                        class="w-7 h-7 object-contain">
                </div>
                <div class="text-[16px] font-semibold text-text mb-2">Musculation</div>
                <div class="text-[13px] text-muted leading-[1.6]">Équipements de dernière génération pour développer
                    votre masse musculaire et votre force.</div>
            </div>
            <div
                class="bg-surface border border-line rounded-2xl p-6 sm:p-7 transition-all duration-200 hover:-translate-y-1.5 hover:shadow-[0_16px_40px_rgba(0,0,0,0.08)] hover:border-orange">
                <div class="w-[52px] h-[52px] rounded-[14px] bg-orange-light flex items-center justify-center mb-4">
                    <img src="{{ asset('images/icons/taekwondo.png') }}" alt="Taekwondo" class="w-7 h-7 object-contain">
                </div>
                <div class="text-[16px] font-semibold text-text mb-2">Taekwondo</div>
                <div class="text-[13px] text-muted leading-[1.6]">Arts martiaux coréens pour développer la discipline,
                    la flexibilité et la confiance en soi.</div>
            </div>
            <div
                class="bg-surface border border-line rounded-2xl p-6 sm:p-7 transition-all duration-200 hover:-translate-y-1.5 hover:shadow-[0_16px_40px_rgba(0,0,0,0.08)] hover:border-orange">
                <div class="w-[52px] h-[52px] rounded-[14px] bg-orange-light flex items-center justify-center mb-4">
                    <img src="{{ asset('images/icons/yoga.png') }}" alt="Yoga" class="w-7 h-7 object-contain">
                </div>
                <div class="text-[16px] font-semibold text-text mb-2">Yoga</div>
                <div class="text-[13px] text-muted leading-[1.6]">Séances de yoga pour améliorer la flexibilité, réduire
                    le stress et renforcer le corps.</div>
            </div>
            <div
                class="bg-surface border border-line rounded-2xl p-6 sm:p-7 transition-all duration-200 hover:-translate-y-1.5 hover:shadow-[0_16px_40px_rgba(0,0,0,0.08)] hover:border-orange">
                <div class="w-[52px] h-[52px] rounded-[14px] bg-orange-light flex items-center justify-center mb-4">
                    <img src="{{ asset('images/icons/tapis-roulant.png') }}" alt="Cardio"
                        class="w-7 h-7 object-contain">
                </div>
                <div class="text-[16px] font-semibold text-text mb-2">Cardio</div>
                <div class="text-[13px] text-muted leading-[1.6]">Machines cardio modernes pour améliorer votre
                    endurance et brûler les calories.</div>
            </div>
            <div
                class="bg-surface border border-line rounded-2xl p-6 sm:p-7 transition-all duration-200 hover:-translate-y-1.5 hover:shadow-[0_16px_40px_rgba(0,0,0,0.08)] hover:border-orange">
                <div class="w-[52px] h-[52px] rounded-[14px] bg-orange-light flex items-center justify-center mb-4">
                    <img src="{{ asset('images/icons/fitness.png') }}" alt="Cours de Fitness"
                        class="w-7 h-7 object-contain">
                </div>
                <div class="text-[16px] font-semibold text-text mb-2">Fitness</div>
                <div class="text-[13px] text-muted leading-[1.6]">Cours de fitness dynamiques pour tonifier votre corps
                    et améliorer votre condition physique.</div>
            </div>
            <div
                class="bg-surface border border-line rounded-2xl p-6 sm:p-7 transition-all duration-200 hover:-translate-y-1.5 hover:shadow-[0_16px_40px_rgba(0,0,0,0.08)] hover:border-orange">
                <div class="w-[52px] h-[52px] rounded-[14px] bg-orange-light flex items-center justify-center mb-4">
                    <img src="{{ asset('images/icons/coaching-executif.png') }}" alt="Coaching Personnalisé"
                        class="w-7 h-7 object-contain">
                </div>
                <div class="text-[16px] font-semibold text-text mb-2">Coaching Personnalisé</div>
                <div class="text-[13px] text-muted leading-[1.6]">Programmes d'entraînement sur mesure avec suivi
                    personnalisé par nos experts.</div>
            </div>
        </div>
    </section>

    <!-- WHY US -->
    <section class="bg-surface py-12 sm:py-20 px-5 sm:px-12">
        <div class="mb-8 sm:mb-12">
            <div class="text-[11px] font-bold text-orange uppercase tracking-[2px] mb-3">Pourquoi Xtrem Gym?</div>
            <div class="text-[26px] sm:text-[36px] font-bold text-text tracking-[-1px] mb-3 leading-[1.2]">L'excellence
                au service<br>de votre forme</div>
            <div class="text-[14px] sm:text-[15px] text-muted max-w-[500px]">Nous offrons une expérience fitness unique
                à Agadir</div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div
                class="flex gap-4 p-5 sm:p-6 bg-page border border-line rounded-[14px] transition-all duration-200 hover:border-orange hover:shadow-[0_8px_24px_rgba(249,115,22,0.08)]">
                <div class="w-11 h-11 rounded-xl bg-orange-light flex items-center justify-center flex-shrink-0">
                    <img src="{{ asset('images/icons/trophee.png') }}" alt="trophee icon"
                        class="w-7 h-7 object-contain">
                </div>
                <div>
                    <div class="text-[14px] font-semibold text-text mb-1">Coachs Certifiés</div>
                    <div class="text-[12.5px] text-muted leading-[1.6]">Tous nos coachs sont certifiés et expérimentés
                        pour vous accompagner vers vos objectifs.</div>
                </div>
            </div>
            <div
                class="flex gap-4 p-5 sm:p-6 bg-page border border-line rounded-[14px] transition-all duration-200 hover:border-orange hover:shadow-[0_8px_24px_rgba(249,115,22,0.08)]">
                <div class="w-11 h-11 rounded-xl bg-orange-light flex items-center justify-center flex-shrink-0">
                    <img src="{{ asset('images/icons/zap.png') }}" alt="zap icon" class="w-7 h-7 object-contain">
                </div>
                <div>
                    <div class="text-[14px] font-semibold text-text mb-1">Équipements Modernes</div>
                    <div class="text-[12.5px] text-muted leading-[1.6]">Des machines de dernière génération pour un
                        entraînement optimal et sécurisé.</div>
                </div>
            </div>
            <div
                class="flex gap-4 p-5 sm:p-6 bg-page border border-line rounded-[14px] transition-all duration-200 hover:border-orange hover:shadow-[0_8px_24px_rgba(249,115,22,0.08)]">
                <div class="w-11 h-11 rounded-xl bg-orange-light flex items-center justify-center flex-shrink-0">
                    <img src="{{ asset('images/icons/temps.png') }}" alt="time icon" class="w-7 h-7 object-contain">
                </div>
                <div>
                    <div class="text-[14px] font-semibold text-text mb-1">Horaires Flexibles</div>
                    <div class="text-[12.5px] text-muted leading-[1.6]">Ouvert 7j/7 avec des horaires adaptés à votre
                        emploi du temps chargé.</div>
                </div>
            </div>
            <div
                class="flex gap-4 p-5 sm:p-6 bg-page border border-line rounded-[14px] transition-all duration-200 hover:border-orange hover:shadow-[0_8px_24px_rgba(249,115,22,0.08)]">
                <div class="w-11 h-11 rounded-xl bg-orange-light flex items-center justify-center flex-shrink-0">
                    <img src="{{ asset('images/icons/donnees-mobiles.png') }}" alt="phone icon"
                        class="w-7 h-7 object-contain">
                </div>
                <div>
                    <div class="text-[14px] font-semibold text-text mb-1">Suivi Digital</div>
                    <div class="text-[12.5px] text-muted leading-[1.6]">Système de gestion moderne pour suivre vos
                        abonnements et vos progrès facilement.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- PLANS -->
    <section id="plans" class="bg-page py-12 sm:py-20 px-5 sm:px-12">
        <div class="mb-8 sm:mb-12">
            <div class="text-[11px] font-bold text-orange uppercase tracking-[2px] mb-3">Nos Abonnements</div>
            <div class="text-[26px] sm:text-[36px] font-bold text-text tracking-[-1px] mb-3">Choisissez votre plan
            </div>
            <div class="text-[14px] sm:text-[15px] text-muted max-w-[500px]">Des tarifs transparents et adaptés à tous
                les budgets</div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($plans as $plan)
                <div
                    class="relative bg-surface border rounded-2xl p-6 sm:p-7 flex flex-col gap-4 transition-all duration-200 {{ $loop->index === 1 ? 'border-orange shadow-[0_8px_32px_rgba(249,115,22,0.15)]' : 'border-line' }}">
                    @if ($loop->index === 1)
                        <div
                            class="absolute -top-3 left-1/2 -translate-x-1/2 bg-orange text-white text-[11px] font-bold px-3.5 py-1 rounded-full whitespace-nowrap">
                            ⭐ Le Plus Populaire</div>
                    @endif
                    <div class="text-[18px] font-bold text-text">{{ $plan->name }}</div>
                    <div class="text-[36px] sm:text-[42px] font-extrabold text-orange font-mono leading-none">
                        {{ number_format($plan->price, 0) }}<span
                            class="text-[13px] sm:text-[14px] font-medium text-muted"> MAD/mois</span>
                    </div>
                    <div class="flex flex-col gap-2">
                        <div
                            class="flex items-center gap-2 text-[13px] text-text before:content-['✓'] before:text-orange before:font-bold">
                            Accès illimité à la salle</div>
                        <div
                            class="flex items-center gap-2 text-[13px] text-text before:content-['✓'] before:text-orange before:font-bold">
                            {{ $plan->duration }} jours de validité</div>
                        <div
                            class="flex items-center gap-2 text-[13px] text-text before:content-['✓'] before:text-orange before:font-bold">
                            Cours collectifs inclus</div>
                        @if ($plan->inscription_fee == 0)
                            <div
                                class="flex items-center gap-2 text-[13px] text-text before:content-['✓'] before:text-orange before:font-bold">
                                Sans frais d'inscription</div>
                        @else
                            <div
                                class="flex items-center gap-2 text-[13px] text-text before:content-['✓'] before:text-orange before:font-bold">
                                Frais d'inscription: {{ $plan->inscription_fee }} MAD</div>
                        @endif
                    </div>
                    <a href="{{ route('login') }}"
                        class="block text-center py-2.5 sm:py-[11px] rounded-[10px] text-white no-underline text-[13px] font-semibold mt-auto {{ $loop->index === 1 ? 'bg-orange' : 'bg-text' }}">
                        Commencer
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <!-- GALLERY -->
    <!-- GALLERY -->
    <section id="gallery" class="bg-surface py-12 sm:py-20 px-5 sm:px-12">
        <div class="mb-8 sm:mb-12">
            <div class="text-[11px] font-bold text-orange uppercase tracking-[2px] mb-3">Notre Salle</div>
            <div class="text-[26px] sm:text-[36px] font-bold text-text tracking-[-1px] mb-3">Découvrez nos
                installations</div>
            <div class="text-[14px] sm:text-[15px] text-muted max-w-[500px]">Des espaces modernes et bien équipés pour
                votre entraînement</div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 auto-rows-[140px] sm:auto-rows-[200px]">
            <div class="rounded-xl overflow-hidden bg-page col-span-2 row-span-2 sm:col-span-1">
                <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800" alt="Gym"
                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
            </div>
            <div class="rounded-xl overflow-hidden bg-page">
                <img src="https://images.unsplash.com/photo-1571902943202-507ec2618e8f?w=800" alt="Weights"
                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
            </div>
            <div class="rounded-xl overflow-hidden bg-page">
                <img src="https://images.unsplash.com/photo-1593079831268-3381b0db4a77?w=800" alt="Cardio"
                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
            </div>
            <div class="rounded-xl overflow-hidden bg-page">
                <img src="https://images.unsplash.com/photo-1540497077202-7c8a3999166f?w=800" alt="Training"
                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
            </div>
            <div class="rounded-xl overflow-hidden bg-page">
                <img src="https://images.unsplash.com/photo-1518611012118-696072aa579a?w=800" alt="Yoga"
                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer
        class="bg-text text-white py-8 sm:py-10 px-5 sm:px-12 flex flex-col sm:flex-row items-center gap-4 sm:gap-0 justify-between text-center sm:text-left">
        <div class="text-[18px] font-extrabold">Xtrem<span class="text-orange">Gym</span></div>
        <div class="text-[13px] text-muted text-center">
            © 2026 XtremGym — Tous droits réservés<br>
            <span class="text-[#4B5563] text-[12px]">Développé avec ❤️ pour Agadir</span>
        </div>
        <div class="text-[13px] text-muted text-center sm:text-right">
            📍 Abattoir, Agadir<br>
            📞 +212 6XX XX XX XX
        </div>
    </footer>

</body>

</html>
