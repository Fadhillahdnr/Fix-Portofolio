export function initializeNavigation() {
    const button = document.querySelector('.menu-button');
    const navigation = document.querySelector('#primary-navigation');

    if (!button || !navigation) {
        return;
    }

    const close = () => {
        navigation.classList.remove('is-open');
        button.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('is-locked');
    };

    button.addEventListener('click', () => {
        const isOpen = navigation.classList.toggle('is-open');

        button.setAttribute('aria-expanded', String(isOpen));
        document.body.classList.toggle('is-locked', isOpen);
    });

    navigation.addEventListener('click', (event) => {
        if (event.target.closest('a')) {
            close();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && navigation.classList.contains('is-open')) {
            close();
            button.focus();
        }
    });
}
