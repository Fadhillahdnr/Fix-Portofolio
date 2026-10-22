export function initializeCaseProgress() {
    const caseStudy = document.querySelector('[data-case-study]');

    if (!caseStudy) {
        return;
    }

    const bar = caseStudy.querySelector('[data-case-progress-bar]');
    const value = caseStudy.querySelector('[data-case-progress-value]');
    const sections = caseStudy.querySelectorAll('[data-case-section]');
    const links = caseStudy.querySelectorAll('[data-case-link]');

    const update = () => {
        const start = caseStudy.offsetTop;
        const distance = caseStudy.offsetHeight - innerHeight;
        const ratio = distance > 0 ? (scrollY - start) / distance : 0;
        const bounded = Math.min(1, Math.max(0, ratio));

        if (bar) {
            bar.style.transform = `scaleX(${bounded})`;
        }

        if (value) {
            const percentage = Math.round(bounded * 100).toString().padStart(2, '0');

            value.textContent = `${percentage}%`;
        }
    };

    const sectionObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                links.forEach((link) => {
                    link.classList.toggle(
                        'is-active',
                        link.dataset.caseLink === entry.target.id,
                    );
                });
            });
        },
        { rootMargin: '-35% 0px -55% 0px' },
    );

    sections.forEach((section) => sectionObserver.observe(section));
    window.addEventListener('scroll', update, { passive: true });
    update();
}
