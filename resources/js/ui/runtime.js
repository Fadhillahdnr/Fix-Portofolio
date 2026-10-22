export function initializeRuntime() {
    const timeElements = document.querySelectorAll('[data-local-time]');
    const progress = document.querySelector('[data-page-progress]');
    const formatter = new Intl.DateTimeFormat('id-ID', {
        timeZone: 'Asia/Jakarta',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false,
    });

    const updateTime = () => {
        const localTime = formatter.format(new Date());

        timeElements.forEach((element) => {
            element.textContent = localTime;
        });
    };

    const updateProgress = () => {
        if (!progress) {
            return;
        }

        const scrollable = document.documentElement.scrollHeight - innerHeight;
        const ratio = scrollable > 0 ? scrollY / scrollable : 0;

        progress.style.transform = `scaleX(${Math.min(1, Math.max(0, ratio))})`;
    };

    updateTime();
    updateProgress();

    window.setInterval(updateTime, 1000);
    window.addEventListener('scroll', updateProgress, { passive: true });
}
