import './bootstrap';
import './core/preferences.js';

import { initializeInterfaceSound } from './audio/interface-sound.js';
import { initializeCaseProgress } from './motion/case-progress.js';
import { initializeConfigPanel } from './ui/config-panel.js';
import { initializeNavigation } from './ui/navigation.js';
import { initializeRuntime } from './ui/runtime.js';

initializeNavigation();
initializeConfigPanel();
initializeRuntime();
initializeCaseProgress();
initializeInterfaceSound();

const scene = document.querySelector('[data-three-scene]');
const prefersReducedMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;
const usesDataSaver = navigator.connection?.saveData ?? false;
const usesSaverProfile = document.documentElement.dataset.renderProfile === 'saver';

if (scene && !prefersReducedMotion && !usesDataSaver && !usesSaverProfile) {
    const observer = new IntersectionObserver(
        async (entries) => {
            if (!entries[0].isIntersecting) {
                return;
            }

            observer.disconnect();

            const { mountHeroScene } = await import('./three/hero-scene.js');

            mountHeroScene(scene);
        },
        { rootMargin: '200px' },
    );

    observer.observe(scene);
}
