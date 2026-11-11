export function t(key) {
    return window.translations?.[key] || key;
}

export function getLocale() {
    return window.locale || 'fr';
}

export function isRTL() {
    return getLocale() === 'ar';
}