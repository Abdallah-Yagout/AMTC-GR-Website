export function t(key, replacements = {}) {
    let str = window.__translations?.[key] ?? key;

    Object.entries(replacements).forEach(([placeholder, value]) => {
        str = str.replace(`:${placeholder}`, String(value));
    });

    return str;
}

export function isRtl() {
    return document.documentElement.dir === 'rtl';
}
