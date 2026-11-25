(function () {
  'use strict';
  const $ = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* Hlavička + FAB */
  const header = $('.site-header'), fab = $('.fab');
  function onScroll() {
    const y = window.scrollY;
    header.classList.toggle('scrolled', y > 30);
    if (fab) fab.classList.toggle('show', y > 600);
    $$('[data-parallax]').forEach(el => {
      const r = el.getBoundingClientRect();
      if (r.bottom < 0 || r.top > innerHeight) return;
      el.style.transform = 'translateY(' + ((r.top - innerHeight / 2) * parseFloat(el.dataset.parallax)) + 'px)';
    });
  }
  let tick = false;
  addEventListener('scroll', () => { if (!tick) { tick = true; requestAnimationFrame(() => { tick = false; onScroll(); }); } }, { passive: true }); onScroll();

  /* Mobilné menu */
  const burger = $('#burger'), nav = $('#mainNav');
  burger.addEventListener('click', () => {
    const open = nav.classList.toggle('open');
    burger.setAttribute('aria-expanded', open);
    document.body.style.overflow = open ? 'hidden' : '';
  });
  $$('a', nav).forEach(a => a.addEventListener('click', () => {
    nav.classList.remove('open'); burger.setAttribute('aria-expanded', false); document.body.style.overflow = '';
  }));

  /* Reveal pri skrolovaní so staggerom */
  const flow = $('#flow'); if (flow) flow.classList.add('reveal');
  $$('.cards,.features,.steps,.stats,.photo-strip,.info-list,.flow').forEach(g =>
    Array.from(g.children).forEach((c, i) => { c.classList.add('reveal'); c.style.setProperty('--d', (i * 0.09) + 's'); }));
  const io = new IntersectionObserver(es => es.forEach(e => {
    if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
  }), { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
  $$('.reveal').forEach(el => io.observe(el));

  /* 3D naklápanie kariet */
  if (!reduce && matchMedia('(hover:hover)').matches) {
    $$('.card').forEach(card => {
      if (!$('.shine', card)) { const s = document.createElement('span'); s.className = 'shine'; card.appendChild(s); }
      card.addEventListener('pointermove', e => {
        const r = card.getBoundingClientRect();
        const x = (e.clientX - r.left) / r.width, y = (e.clientY - r.top) / r.height;
        card.style.transform = 'rotateY(' + ((x - .5) * 12) + 'deg) rotateX(' + ((.5 - y) * 10) + 'deg) translateY(-6px)';
        card.style.setProperty('--mx', x * 100 + '%'); card.style.setProperty('--my', y * 100 + '%');
      });
      card.addEventListener('pointerleave', () => { card.style.transform = ''; });
    });
  }

  /* Počítadlá */
  const cio = new IntersectionObserver(es => es.forEach(e => {
    if (!e.isIntersecting) return;
    cio.unobserve(e.target);
    const el = e.target, end = parseFloat(el.dataset.count), suf = el.dataset.suffix || '';
    const t0 = performance.now(), dur = 1800;
    (function tick(t) {
      const p = Math.min(1, (t - t0) / dur), v = 1 - Math.pow(1 - p, 3);
      el.textContent = Math.round(end * v).toLocaleString('sk-SK') + suf;
      if (p < 1) requestAnimationFrame(tick);
    })(t0);
  }), { threshold: .6 });
  $$('[data-count]').forEach(el => cio.observe(el));

  /* Slider ukážok: šípky, ťahanie myšou, ukazovateľ postupu */
  $$('.slider').forEach(sl => {
    const tr = $('.track', sl), bar = $('.bar-progress i', sl);
    const step = () => ($('a', tr).offsetWidth + 22) * (innerWidth < 700 ? 1 : 2);
    const prog = () => {
      const max = tr.scrollWidth - tr.clientWidth, w = tr.clientWidth / tr.scrollWidth;
      bar.style.width = (w * 100) + '%';
      bar.style.marginLeft = (max > 0 ? (tr.scrollLeft / max) * (100 - w * 100) : 0) + '%';
    };
    tr.addEventListener('scroll', prog, { passive: true }); addEventListener('resize', prog); prog();
    $('.s-prev', sl).onclick = () => tr.scrollBy({ left: -step(), behavior: 'smooth' });
    $('.s-next', sl).onclick = () => {
      if (tr.scrollLeft + tr.clientWidth >= tr.scrollWidth - 8) tr.scrollTo({ left: 0, behavior: 'smooth' });
      else tr.scrollBy({ left: step(), behavior: 'smooth' });
    };
    tr.addEventListener('keydown', e => {
      if (e.key === 'ArrowRight') tr.scrollBy({ left: step() / 2, behavior: 'smooth' });
      if (e.key === 'ArrowLeft') tr.scrollBy({ left: -step() / 2, behavior: 'smooth' });
    });
    let down = false, sx = 0, sl0 = 0, moved = false;
    tr.addEventListener('pointerdown', e => {
      if (e.pointerType !== 'mouse') return;
      down = true; moved = false; sx = e.clientX; sl0 = tr.scrollLeft;
    });
    addEventListener('pointermove', e => {
      if (!down) return;
      const dx = e.clientX - sx;
      if (Math.abs(dx) > 5) { moved = true; tr.classList.add('drag'); }
      if (moved) tr.scrollLeft = sl0 - dx;
    });
    addEventListener('pointerup', () => {
      if (!down) return; down = false; tr.classList.remove('drag');
      if (moved) { const w = $('a', tr).offsetWidth + 22; tr.scrollTo({ left: Math.round(tr.scrollLeft / w) * w, behavior: 'smooth' }); }
    });
    tr.addEventListener('click', e => { if (moved) { e.preventDefault(); e.stopImmediatePropagation(); moved = false; } }, true);
  });

  /* Lightbox */
  const lbLinks = $$('[data-lightbox]');
  if (lbLinks.length) {
    const lb = document.createElement('div');
    lb.className = 'lightbox'; lb.setAttribute('role', 'dialog'); lb.setAttribute('aria-modal', 'true');
    lb.innerHTML = '<button class="lb-close" aria-label="Zavrieť">×</button><button class="lb-prev" aria-label="Predchádzajúca">‹</button><img alt=""><button class="lb-next" aria-label="Ďalšia">›</button><div class="lb-cap"></div>';
    document.body.appendChild(lb);
    const img = $('img', lb), cap = $('.lb-cap', lb);
    let list = [], idx = 0;
    const show = i => { idx = (i + list.length) % list.length; img.src = list[idx].href; img.alt = list[idx].dataset.label || ''; cap.textContent = list[idx].dataset.label || ''; };
    const close = () => { lb.classList.remove('open'); document.body.style.overflow = ''; };
    lbLinks.forEach(a => a.addEventListener('click', e => {
      e.preventDefault();
      list = lbLinks.filter(x => !x.classList.contains('hide'));
      show(list.indexOf(a)); lb.classList.add('open'); document.body.style.overflow = 'hidden';
    }));
    $('.lb-close', lb).onclick = close;
    $('.lb-prev', lb).onclick = () => show(idx - 1);
    $('.lb-next', lb).onclick = () => show(idx + 1);
    lb.addEventListener('click', e => { if (e.target === lb) close(); });
    addEventListener('keydown', e => {
      if (!lb.classList.contains('open')) return;
      if (e.key === 'Escape') close();
      if (e.key === 'ArrowLeft') show(idx - 1);
      if (e.key === 'ArrowRight') show(idx + 1);
    });
  }

  /* Filter galérie */
  $$('.filters button').forEach(b => b.addEventListener('click', () => {
    $$('.filters button').forEach(x => x.classList.remove('on')); b.classList.add('on');
    const f = b.dataset.filter;
    $$('.masonry a').forEach(a => a.classList.toggle('hide', f !== 'all' && a.dataset.cat !== f));
  }));

  /* Prechody medzi stránkami */
  addEventListener('pageshow', e => { if (e.persisted) $('.page-curtain').classList.remove('in'); });
  if (!reduce) {
    document.addEventListener('click', e => {
      const a = e.target.closest('a');
      if (!a || a.target === '_blank' || e.metaKey || e.ctrlKey || e.shiftKey) return;
      const u = new URL(a.href, location.href);
      if (u.origin !== location.origin || u.pathname === location.pathname && u.hash) return;
      if (/^(mailto|tel):/.test(a.getAttribute('href')) || a.hasAttribute('download') || a.hasAttribute('data-lightbox')) return;
      e.preventDefault();
      $('.page-curtain').classList.add('in');
      setTimeout(() => { location.href = a.href; }, 220);
    });
  }

  /* Kontaktný formulár */
  const cf = $('#contactForm');
  if (cf) cf.addEventListener('submit', async e => {
    e.preventDefault();
    const msg = $('#cfMsg'), btn = $('button[type=submit]', cf);
    btn.disabled = true; msg.className = ''; msg.textContent = '';
    const data = Object.fromEntries(new FormData(cf));
    data.elapsed = Math.round((Date.now() - cf.dataset.t) / 1000);
    try {
      const r = await fetch('api/contact.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
      const j = await r.json();
      msg.className = 'alert ' + (j.ok ? 'ok' : 'bad');
      msg.textContent = j.ok ? (cf.dataset.ok || 'Ďakujem, správu som prijala.') : (j.error || 'Správu sa nepodarilo odoslať.');
      if (j.ok) cf.reset();
    } catch (err) { msg.className = 'alert bad'; msg.textContent = 'Chyba spojenia. Skúste to prosím znova.'; }
    btn.disabled = false;
  });
  if (cf) cf.dataset.t = Date.now();
})();
