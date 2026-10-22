import {
    getPreferences,
    resetPreferences,
    setPreference,
} from '../core/preferences.js';

export function initializeConfigPanel() {
    const panel = document.querySelector('#interface-config');
    const openButton = document.querySelector('[data-config-open]');
    const closeButtons = document.querySelectorAll('[data-config-close]');
    const backdrop = document.querySelector('.config-backdrop');

    if (!panel || !openButton || !backdrop) {
        return;
    }

    let hideTimer;

    const syncControls = () => {
        const preferences = getPreferences();

        panel.querySelectorAll('[data-preference-group]').forEach((group) => {
            const name = group.dataset.preferenceGroup;

            group.querySelectorAll('button').forEach((button) => {
                const isActive = button.dataset.value === preferences[name];

                button.classList.toggle('is-active', isActive);
                button.setAttribute('aria-pressed', String(isActive));
            });
        });
    };

    const close = () => {
        window.clearTimeout(hideTimer);
        panel.classList.remove('is-open');
        backdrop.classList.remove('is-open');
        panel.setAttribute('aria-hidden', 'true');
        panel.setAttribute('inert', '');
        openButton.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('is-locked');
        openButton.focus();

        hideTimer = window.setTimeout(() => {
            if (!panel.classList.contains('is-open')) {
                panel.hidden = true;
            }
        }, 350);
    };

    const open = () => {
        window.clearTimeout(hideTimer);
        syncControls();
        panel.hidden = false;
        panel.getBoundingClientRect();
        panel.classList.add('is-open');
        backdrop.classList.add('is-open');
        panel.setAttribute('aria-hidden', 'false');
        panel.removeAttribute('inert');
        openButton.setAttribute('aria-expanded', 'true');
        document.body.classList.add('is-locked');
        panel.querySelector('button')?.focus();
    };

    openButton.addEventListener('click', open);
    closeButtons.forEach((button) => button.addEventListener('click', close));

    panel.addEventListener('click', (event) => {
        const preferenceButton = event.target.closest('[data-preference-group] button');

        if (preferenceButton) {
            const group = preferenceButton.closest('[data-preference-group]');

            setPreference(group.dataset.preferenceGroup, preferenceButton.dataset.value);
            syncControls();
        }

        if (event.target.closest('[data-preferences-reset]')) {
            resetPreferences();
            syncControls();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && panel.classList.contains('is-open')) {
            close();
        }
    });

    syncControls();
}
