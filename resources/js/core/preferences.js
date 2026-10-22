const storageKeys = {
    theme: 'portfolio.theme',
    render: 'portfolio.performance',
    motion: 'portfolio.motion',
    sound: 'portfolio.sound',
};

const defaults = {
    theme: matchMedia('(prefers-color-scheme: light)').matches ? 'minimal' : 'engineering',
    render: navigator.connection?.saveData ? 'saver' : 'balanced',
    motion: 'system',
    sound: 'off',
};

function readPreference(name) {
    try {
        return localStorage.getItem(storageKeys[name]) ?? defaults[name];
    } catch {
        return defaults[name];
    }
}

export function getPreferences() {
    return Object.fromEntries(
        Object.keys(defaults).map((name) => [name, readPreference(name)]),
    );
}

export function applyPreferences(preferences = getPreferences()) {
    const root = document.documentElement;

    root.dataset.theme = preferences.theme;
    root.dataset.renderProfile = preferences.render;
    root.dataset.motion = preferences.motion;
    root.dataset.sound = preferences.sound;

    document.querySelectorAll('[data-runtime-render]').forEach((element) => {
        element.textContent = preferences.render;
    });

    window.dispatchEvent(
        new CustomEvent('portfolio:preferences', { detail: preferences }),
    );
}

export function setPreference(name, value) {
    try {
        localStorage.setItem(storageKeys[name], value);
    } catch {
        // Storage can be unavailable in strict privacy modes; the session still works.
    }

    applyPreferences(getPreferences());
}

export function resetPreferences() {
    try {
        Object.values(storageKeys).forEach((key) => localStorage.removeItem(key));
    } catch {
        // Keep defaults in memory when persistent storage is unavailable.
    }

    applyPreferences(defaults);
}

applyPreferences();
