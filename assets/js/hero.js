/* Hero: padajúce lupienky (ľahký Canvas 2D, bez knižníc) + jemné naklápanie fotky podľa myši. */
(function () {
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* Naklápanie kompozície v úvodnej sekcii */
  const vis = document.getElementById('heroVisual');
  if (vis && !reduce) {
    const stage = vis.querySelector('.stage');
    const hero = vis.closest('.hero');
    hero.addEventListener('pointermove', e => {
      const x = e.clientX / innerWidth - 0.5, y = e.clientY / innerHeight - 0.5;
      stage.style.transform = 'rotateY(' + (x * 16) + 'deg) rotateX(' + (-y * 12) + 'deg)';
    }, { passive: true });
    hero.addEventListener('pointerleave', () => { stage.style.transform = ''; });
  }

  /* Lupienky */
  const canvas = document.getElementById('petals');
  if (!canvas || reduce) return;
  const ctx = canvas.getContext('2d');
  if (!ctx) return;

  // Slabšie zariadenia dostanú menej lupienkov
  const weak = (navigator.hardwareConcurrency || 4) <= 2 || (navigator.deviceMemory || 4) <= 2;
  const N = weak ? 5 : (innerWidth < 700 ? 7 : 12);
  const cols = ['#82b0d8', '#6fb0e3', '#c7e0f5', '#f3ead6', '#b5d1e9', '#3d93d6'];
  const petal = new Path2D();
  petal.moveTo(0, 0);
  petal.bezierCurveTo(0.55, 0.25, 0.6, 0.9, 0, 1.3);
  petal.bezierCurveTo(-0.6, 0.9, -0.55, 0.25, 0, 0);

  let W = 0, H = 0;
  function size() { W = canvas.width = innerWidth; H = canvas.height = innerHeight; }
  size();
  let rt; addEventListener('resize', () => { clearTimeout(rt); rt = setTimeout(size, 150); });

  const items = [];
  function reset(p, initial) {
    p.x = Math.random() * W;
    p.y = initial ? Math.random() * H : -H * 0.12 - Math.random() * H * 0.2;
    p.size = (0.25 + Math.random() * 0.45) * H / 9.3;
    p.vy = (0.3 + Math.random() * 0.6) * H / 9.3;      // px / s
    p.sw = 0.4 + Math.random(); p.ph = Math.random() * 6; p.amp = 0.08 * W * (0.5 + Math.random());
    p.rot = Math.random() * 6.28; p.vr = (Math.random() - 0.5) * 1.2;
    p.fl = Math.random() * 6.28; p.vf = 0.6 + Math.random() * 0.8;
    p.x0 = p.x; p.col = cols[(Math.random() * cols.length) | 0];
  }
  for (let i = 0; i < N; i++) { const p = {}; reset(p, true); items.push(p); }

  let last = 0, running = true;
  function frame(now) {
    if (!running) return;
    requestAnimationFrame(frame);
    if (now - last < 33) return;                       // max. ~30 snímok/s
    const dt = Math.min(0.1, (now - last) / 1000); last = now;
    const t = now / 1000;
    ctx.clearRect(0, 0, W, H);
    ctx.globalAlpha = 0.3;
    for (const p of items) {
      p.y += p.vy * dt;
      p.rot += p.vr * dt; p.fl += p.vf * dt;
      const x = p.x0 + Math.sin(t * p.sw + p.ph) * p.amp;
      if (p.y > H + p.size * 1.4) reset(p, false);
      ctx.save();
      ctx.translate(x, p.y); ctx.rotate(p.rot);
      ctx.scale(p.size, p.size * Math.cos(p.fl));      // „preklápanie“ lupienka
      ctx.fillStyle = p.col; ctx.fill(petal);
      ctx.restore();
    }
  }
  // Nebežať na skrytej karte
  document.addEventListener('visibilitychange', () => {
    running = !document.hidden;
    if (running) { last = performance.now(); requestAnimationFrame(frame); }
  });
  requestAnimationFrame(frame);
})();
