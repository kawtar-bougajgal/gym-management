<div style="position:relative;display:inline-block;" id="lang-switcher">
    <button onclick="toggleLangMenu()"
        style="display:flex;align-items:center;gap:6px;padding:6px 12px;border:1px solid var(--border);border-radius:8px;background:var(--surface);cursor:pointer;font-family:'DM Sans',sans-serif;font-size:12.5px;font-weight:600;color:var(--text);">
        @if(app()->getLocale() === 'fr')
            🇫🇷 FR
        @elseif(app()->getLocale() === 'en')
            🇬🇧 EN
        @else
            🇲🇦 AR
        @endif
        <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <polyline points="6 9 12 15 18 9"/>
        </svg>
    </button>

    <div id="lang-menu"
        style="display:none;position:absolute;top:calc(100% + 6px);right:0;background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:6px;z-index:999;min-width:120px;box-shadow:0 8px 24px rgba(0,0,0,0.08);">
        <a href="{{ route('lang.switch', 'fr') }}"
            style="display:flex;align-items:center;gap:8px;padding:7px 10px;border-radius:7px;text-decoration:none;font-size:12.5px;font-weight:500;color:{{ app()->getLocale() === 'fr' ? 'var(--orange)' : 'var(--text)' }};background:{{ app()->getLocale() === 'fr' ? 'var(--orange-light)' : 'transparent' }};">
            🇫🇷 Français
        </a>
        <a href="{{ route('lang.switch', 'en') }}"
            style="display:flex;align-items:center;gap:8px;padding:7px 10px;border-radius:7px;text-decoration:none;font-size:12.5px;font-weight:500;color:{{ app()->getLocale() === 'en' ? 'var(--orange)' : 'var(--text)' }};background:{{ app()->getLocale() === 'en' ? 'var(--orange-light)' : 'transparent' }};">
            🇬🇧 English
        </a>
        <a href="{{ route('lang.switch', 'ar') }}"
            style="display:flex;align-items:center;gap:8px;padding:7px 10px;border-radius:7px;text-decoration:none;font-size:12.5px;font-weight:500;color:{{ app()->getLocale() === 'ar' ? 'var(--orange)' : 'var(--text)' }};background:{{ app()->getLocale() === 'ar' ? 'var(--orange-light)' : 'transparent' }};">
            🇲🇦 العربية
        </a>
    </div>
</div>

<script>
    function toggleLangMenu() {
        const menu = document.getElementById('lang-menu');
        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    }
    document.addEventListener('click', e => {
        if (!e.target.closest('#lang-switcher')) {
            const menu = document.getElementById('lang-menu');
            if (menu) menu.style.display = 'none';
        }
    });
</script>