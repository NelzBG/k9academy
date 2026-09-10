import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';

const stage = document.querySelector('[data-dog-stage]');

if (stage) {
    const canvas = stage.querySelector('canvas');
    const loading = stage.querySelector('[data-model-loading]');
    const modelUrl = stage.dataset.model;
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(34, 1, 0.1, 100);
    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true, powerPreference: 'high-performance' });
    const controls = new OrbitControls(camera, canvas);
    const timer = new THREE.Timer();
    let mixer = null;
    let animations = [];
    let currentAction = null;
    let requestedAnimation = 'idle';

    const clipNames = {
        idle: 'Idle Breathing',
        play: 'Idle Playing',
        walk: 'Walk',
        run: 'Run',
    };

    renderer.setPixelRatio(Math.min(Math.max(window.devicePixelRatio, 1.5), 2));
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.15;
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFShadowMap;
    timer.connect(document);

    camera.position.set(4.2, 2.2, 5.6);
    controls.target.set(0, 1.05, 0);
    controls.enableDamping = true;
    controls.enablePan = false;
    controls.minDistance = 3.1;
    controls.maxDistance = 8.5;
    controls.minPolarAngle = Math.PI * .25;
    controls.maxPolarAngle = Math.PI * .52;

    scene.add(new THREE.HemisphereLight(0xfff7df, 0x4d3f2c, 3.2));
    const keyLight = new THREE.DirectionalLight(0xffffff, 4.4);
    keyLight.position.set(4, 7, 5);
    keyLight.castShadow = true;
    keyLight.shadow.mapSize.set(2048, 2048);
    keyLight.shadow.bias = -0.0003;
    keyLight.shadow.normalBias = 0.015;
    scene.add(keyLight);
    const rimLight = new THREE.DirectionalLight(0xd9ff43, 2.4);
    rimLight.position.set(-4, 3, -4);
    scene.add(rimLight);

    const floor = new THREE.Mesh(
        new THREE.CircleGeometry(3.25, 72),
        new THREE.MeshStandardMaterial({ color: 0x786541, roughness: .92, metalness: 0, transparent: true, opacity: .32 })
    );
    floor.rotation.x = -Math.PI / 2;
    floor.receiveShadow = true;
    scene.add(floor);

    const resize = () => {
        const box = stage.getBoundingClientRect();
        if (!box.width || !box.height) return;
        camera.aspect = box.width / box.height;
        camera.updateProjectionMatrix();
        renderer.setSize(box.width, box.height, false);
    };

    const findClip = (token) => {
        const expectedName = clipNames[token];
        return animations.find((clip) => clip.name === expectedName) || null;
    };
    const play = (token) => {
        requestedAnimation = token;
        if (!mixer || !animations.length) return;
        const clip = findClip(token);
        if (!clip) return;
        const nextAction = mixer.clipAction(clip);
        nextAction.enabled = true;
        nextAction.setEffectiveTimeScale(1);
        nextAction.setEffectiveWeight(1);
        nextAction.reset().setLoop(THREE.LoopRepeat, Infinity).play();
        if (currentAction && currentAction !== nextAction) currentAction.crossFadeTo(nextAction, .28, false);
        currentAction = nextAction;
        document.querySelectorAll('[data-animation]').forEach((button) => button.classList.toggle('is-active', button.dataset.animation === token));
    };

    new GLTFLoader().load(modelUrl, (gltf) => {
        const dog = gltf.scene;
        const box = new THREE.Box3().setFromObject(dog);
        const size = box.getSize(new THREE.Vector3());
        const center = box.getCenter(new THREE.Vector3());
        const scale = 3 / Math.max(size.x, size.y, size.z);
        dog.scale.setScalar(scale);
        dog.position.set(-center.x * scale, -box.min.y * scale, -center.z * scale);
        dog.traverse((object) => {
            if (!object.isMesh) return;
            object.castShadow = true;
            object.receiveShadow = true;
            const materials = Array.isArray(object.material) ? object.material : [object.material];
            materials.filter(Boolean).forEach((material) => {
                for (const name of ['map', 'normalMap', 'roughnessMap']) {
                    if (material[name]) material[name].anisotropy = Math.min(8, renderer.capabilities.getMaxAnisotropy());
                }
                material.needsUpdate = true;
            });
        });
        scene.add(dog);
        animations = gltf.animations || [];
        if (animations.length) {
            mixer = new THREE.AnimationMixer(dog);
            play(requestedAnimation);
        }
        loading?.classList.add('is-hidden');
        stage.dispatchEvent(new CustomEvent('k9:model-ready', {
            detail: {
                animations: animations.map((clip) => clip.name),
                controls: { ...clipNames },
            },
        }));
    }, undefined, () => {
        if (!loading) return;
        loading.classList.add('is-error');
        const copy = loading.querySelector('p');
        if (copy) copy.textContent = document.documentElement.lang === 'bg' ? '3D моделът не можа да се зареди. Моля, опитайте отново.' : 'The 3D model could not load. Please try again.';
    });

    document.querySelectorAll('[data-animation]').forEach((button) => button.addEventListener('click', () => play(button.dataset.animation || 'idle')));
    new ResizeObserver(resize).observe(stage);
    resize();

    const animate = (timestamp) => {
        timer.update(timestamp);
        const delta = Math.min(timer.getDelta(), .05);
        if (mixer) mixer.update(delta);
        controls.update();
        renderer.render(scene, camera);
        requestAnimationFrame(animate);
    };
    animate();
}
