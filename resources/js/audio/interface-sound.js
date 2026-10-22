export function initializeInterfaceSound() {
    let audioContext;

    const playTick = () => {
        if (document.documentElement.dataset.sound !== 'fx') {
            return;
        }

        audioContext ??= new AudioContext();

        const oscillator = audioContext.createOscillator();
        const gain = audioContext.createGain();
        const now = audioContext.currentTime;

        oscillator.type = 'sine';
        oscillator.frequency.setValueAtTime(520, now);
        gain.gain.setValueAtTime(0.025, now);
        gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.045);
        oscillator.connect(gain).connect(audioContext.destination);
        oscillator.start(now);
        oscillator.stop(now + 0.05);
    };

    document.addEventListener('click', (event) => {
        if (event.target.closest('a, button')) {
            playTick();
        }
    });
}
