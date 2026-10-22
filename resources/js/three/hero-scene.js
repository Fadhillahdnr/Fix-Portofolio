import * as THREE from 'three';

export function mountHeroScene(container) {
    const orbit = container.querySelector('.runtime-orbit') ?? container;
    const fallback = container.querySelector('.orb-fallback');
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(40, 1, 0.1, 100);
    const renderer = new THREE.WebGLRenderer({
        alpha: true,
        antialias: true,
        powerPreference: 'low-power',
    });
    const geometry = new THREE.IcosahedronGeometry(1.55, 3);
    const material = new THREE.MeshStandardMaterial({
        color: 0xc7f759,
        roughness: 0.28,
        metalness: 0.15,
        flatShading: true,
    });
    const mesh = new THREE.Mesh(geometry, material);
    const light = new THREE.DirectionalLight(0xffffff, 2.5);

    let active = true;
    let inViewport = true;
    let frame;
    let pointerX = 0;
    let pointerY = 0;

    camera.position.z = 5;
    renderer.setPixelRatio(Math.min(devicePixelRatio, 1.5));
    orbit.prepend(renderer.domElement);

    scene.add(mesh);
    scene.add(new THREE.HemisphereLight(0xf4ffe0, 0x10180c, 2.5));

    light.position.set(3, 4, 5);
    scene.add(light);

    const updatePointer = (event) => {
        const bounds = container.getBoundingClientRect();

        pointerX = ((event.clientX - bounds.left) / bounds.width - 0.5) * 0.35;
        pointerY = ((event.clientY - bounds.top) / bounds.height - 0.5) * 0.25;
    };

    const resize = () => {
        const width = orbit.clientWidth;
        const height = orbit.clientHeight;

        camera.aspect = width / height;
        camera.updateProjectionMatrix();
        renderer.setSize(width, height, false);
    };

    const render = () => {
        if (!active) {
            return;
        }

        mesh.rotation.y += (pointerX - mesh.rotation.y) * 0.035;
        mesh.rotation.x += (pointerY - mesh.rotation.x) * 0.035;
        mesh.rotation.z += 0.0015;
        renderer.render(scene, camera);

        frame = requestAnimationFrame(render);
    };

    const synchronizeRendering = () => {
        const usesSaverProfile = document.documentElement.dataset.renderProfile === 'saver';

        active = inViewport && !document.hidden && !usesSaverProfile;
        renderer.domElement.hidden = usesSaverProfile;
        cancelAnimationFrame(frame);

        if (active) {
            render();
        }
    };

    container.addEventListener('pointermove', updatePointer, { passive: true });

    new ResizeObserver(resize).observe(orbit);
    resize();
    fallback?.remove();
    render();

    document.addEventListener('visibilitychange', synchronizeRendering);
    window.addEventListener('portfolio:preferences', synchronizeRendering);

    new IntersectionObserver(([entry]) => {
        inViewport = entry.isIntersecting;
        synchronizeRendering();
    }).observe(container);

    window.addEventListener(
        'pagehide',
        () => {
            active = false;
            cancelAnimationFrame(frame);
            geometry.dispose();
            material.dispose();
            renderer.dispose();
        },
        { once: true },
    );
}
