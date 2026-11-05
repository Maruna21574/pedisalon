/* Hero: jemne padajúce 3D lupienky a lístky (Three.js) + 3D naklápanie fotiek podľa myši. */
(function () {
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* Naklápanie kompozície */
  const vis = document.getElementById('heroVisual');
  if (vis && !reduce) {
    const stage = vis.querySelector('.stage');
    const hero = vis.closest('.hero');
    hero.addEventListener('pointermove', e => {
      const x = e.clientX / innerWidth - 0.5, y = e.clientY / innerHeight - 0.5;
      stage.style.transform = 'rotateY(' + (x * 16) + 'deg) rotateX(' + (-y * 12) + 'deg)';
    });
    hero.addEventListener('pointerleave', () => { stage.style.transform = ''; });
  }

  /* Lupienky */
  const canvas = document.getElementById('petals');
  if (!canvas || typeof THREE === 'undefined' || reduce) return;
  let renderer;
  try { renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true }); } catch (e) { return; }
  renderer.setPixelRatio(Math.min(devicePixelRatio || 1, 2));

  const scene = new THREE.Scene();
  const cam = new THREE.PerspectiveCamera(50, 1, 0.1, 100);
  cam.position.z = 10;
  scene.add(new THREE.AmbientLight(0xffffff, 0.85));
  const sun = new THREE.DirectionalLight(0xffffff, 0.6); sun.position.set(2, 4, 5); scene.add(sun);

  const shape = new THREE.Shape();
  shape.moveTo(0, 0);
  shape.bezierCurveTo(0.55, 0.25, 0.6, 0.9, 0, 1.3);
  shape.bezierCurveTo(-0.6, 0.9, -0.55, 0.25, 0, 0);
  const geo = new THREE.ShapeGeometry(shape, 16);
  const cols = [0xf4a3b0, 0xee93a1, 0xf9ccd2, 0x98b496, 0xbcd0ba, 0xe3708a];
  const mats = cols.map(c => new THREE.MeshLambertMaterial({ color: c, side: THREE.DoubleSide, transparent: true, opacity: 0.32 }));

  const N = innerWidth < 700 ? 12 : 22;
  const items = [];
  let W = 8; // polovica viditeľnej šírky
  function reset(m, initial) {
    m.position.set((Math.random() - 0.5) * 2 * W, initial ? (Math.random() - 0.5) * 12 : 6 + Math.random() * 3, -4 + Math.random() * 4);
    const s = 0.25 + Math.random() * 0.45; m.scale.setScalar(s);
    m.userData = { vy: 0.15 + Math.random() * 0.3, sw: 0.4 + Math.random(), ph: Math.random() * 6, rx: (Math.random() - 0.5) * 1.2, ry: (Math.random() - 0.5) * 1.2, rz: (Math.random() - 0.5) * 0.8, x0: m.position.x };
  }
  function size() {
    const w = canvas.clientWidth, h = canvas.clientHeight;
    renderer.setSize(w, h, false); cam.aspect = w / h; cam.updateProjectionMatrix();
    W = Math.tan(25 * Math.PI / 180) * 10 * cam.aspect * 1.1;
  }
  size(); addEventListener('resize', size);
  for (let i = 0; i < N; i++) { const m = new THREE.Mesh(geo, mats[i % mats.length]); reset(m, true); m.rotation.set(Math.random() * 6, Math.random() * 6, Math.random() * 6); scene.add(m); items.push(m); }

  let vis2 = true;
  new IntersectionObserver(e => { vis2 = e[0].isIntersecting; if (vis2) { last = performance.now(); loop(); } }).observe(canvas);
  let last = performance.now();
  function loop() {
    if (!vis2) return;
    const now = performance.now(), dt = Math.min(0.05, (now - last) / 1000); last = now;
    const t = now / 1000;
    items.forEach(m => {
      const d = m.userData;
      m.position.y -= d.vy * dt * 2;
      m.position.x = d.x0 + Math.sin(t * d.sw + d.ph) * 0.8;
      m.rotation.x += d.rx * dt; m.rotation.y += d.ry * dt; m.rotation.z += d.rz * dt;
      if (m.position.y < -6) reset(m, false);
    });
    renderer.render(scene, cam);
    requestAnimationFrame(loop);
  }
  loop();
})();
