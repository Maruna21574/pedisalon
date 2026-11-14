(function () {
  'use strict';
  const root = document.getElementById('booking');
  if (!root) return;
  const cfg = JSON.parse(root.dataset.config);
  const $ = (s, c = root) => c.querySelector(s);
  const $$ = (s, c = root) => Array.from(c.querySelectorAll(s));
  const MONTHS = ['január', 'február', 'marec', 'apríl', 'máj', 'jún', 'júl', 'august', 'september', 'október', 'november', 'december'];
  const DAYS = ['Po', 'Ut', 'St', 'Št', 'Pi', 'So', 'Ne'];
  const DLONG = ['nedeľa', 'pondelok', 'utorok', 'streda', 'štvrtok', 'piatok', 'sobota'];
  const MLONG = ['januára', 'februára', 'marca', 'apríla', 'mája', 'júna', 'júla', 'augusta', 'septembra', 'októbra', 'novembra', 'decembra'];

  const st = { step: 1, service: null, date: null, time: null, month: null, t0: Date.now() };
  const pad = n => String(n).padStart(2, '0');
  const iso = d => d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
  const money = n => String(n).replace('.', ',') + ' €';
  const nice = s => { const d = new Date(s + 'T12:00:00'); return DLONG[d.getDay()] + ' ' + d.getDate() + '. ' + MLONG[d.getMonth()]; };

  /* ---- Navigácia krokov ---- */
  const stepEls = $$('.bk-step'), stepper = $$('#stepper li');
  function go(n) {
    st.step = n;
    stepEls.forEach(el => el.classList.toggle('on', el.dataset.step == n));
    stepper.forEach((li, i) => {
      li.classList.toggle('done', typeof n === 'number' ? i + 1 < n : true);
      li.classList.toggle('cur', i + 1 === n);
    });
    const top = root.getBoundingClientRect().top + scrollY - 110;
    if (scrollY > top) scrollTo({ top, behavior: 'smooth' });
  }
  stepper.forEach((li, i) => li.addEventListener('click', () => {
    if (st.step === 'done') return;
    if (i + 1 < st.step) go(i + 1);
  }));
  $$('[data-back]').forEach(b => b.addEventListener('click', () => go(st.step - 1)));

  /* ---- Súhrn ---- */
  function summary() {
    const s = st.service && cfg.services[st.service];
    const set = (id, txt, empty) => { const el = $(id); el.textContent = txt; el.classList.toggle('empty', !!empty); };
    set('#sumSvc', s ? s.name : cfg.texts.unset, !s);
    set('#sumDur', s ? s.duration + ' minút' : '–', !s);
    $('#sumPrice').textContent = s ? money(s.price) : '–';
    set('#sumWhen', st.date ? nice(st.date) + (st.time ? ' o ' + st.time : '') : cfg.texts.unset, !st.date);
  }

  /* ---- 1: služba ---- */
  $$('.svc-opt').forEach(b => b.addEventListener('click', () => {
    st.service = b.dataset.id; st.date = st.time = null;
    $$('.svc-opt').forEach(x => x.classList.toggle('sel', x === b));
    summary();
    initMonth(); go(2);
  }));

  /* ---- 2: kalendár ---- */
  const today = new Date(); today.setHours(0, 0, 0, 0);
  const maxDate = new Date(today); maxDate.setDate(maxDate.getDate() + cfg.maxDays);
  let cache = {};

  function initMonth() { st.month = new Date(today.getFullYear(), today.getMonth(), 1); cache = {}; renderCal(); }
  async function monthData(y, m) {
    const key = y + '-' + pad(m + 1) + '|' + st.service;
    if (!cache[key]) {
      const r = await fetch('api/availability.php?service=' + encodeURIComponent(st.service) + '&month=' + y + '-' + pad(m + 1));
      cache[key] = (await r.json()).days || {};
    }
    return cache[key];
  }
  async function renderCal() {
    const y = st.month.getFullYear(), m = st.month.getMonth();
    $('#calTitle').textContent = MONTHS[m] + ' ' + y;
    $('#calPrev').disabled = y === today.getFullYear() && m === today.getMonth();
    $('#calNext').disabled = new Date(y, m + 1, 1) > maxDate;
    const cal = $('#cal');
    cal.innerHTML = '<div class="empty-msg" style="grid-column:1/-1">Načítavam…</div>';
    let data = {};
    try { data = await monthData(y, m); } catch (e) { cal.innerHTML = '<div class="empty-msg" style="grid-column:1/-1">Nepodarilo sa načítať kalendár. Skúste to znova.</div>'; return; }
    let html = DAYS.map(d => '<div class="dow">' + d + '</div>').join('');
    const first = (new Date(y, m, 1).getDay() + 6) % 7, n = new Date(y, m + 1, 0).getDate();
    for (let i = 0; i < first; i++) html += '<div class="empty"></div>';
    for (let d = 1; d <= n; d++) {
      const ds = y + '-' + pad(m + 1) + '-' + pad(d), free = (data[ds] || 0) > 0;
      const isToday = ds === iso(today);
      html += '<button type="button" data-d="' + ds + '" class="' + (free ? 'free' : 'off') + (isToday ? ' today' : '') + (st.date === ds ? ' sel' : '') +
        '"' + (free ? ' title="Voľných termínov: ' + data[ds] + '"' : ' disabled') + '>' + d + '</button>';
    }
    cal.innerHTML = html;
    $$('.free', cal).forEach(b => b.addEventListener('click', () => {
      st.date = b.dataset.d; st.time = null; summary();
      $$('.cal button').forEach(x => x.classList.toggle('sel', x === b));
      loadSlots();
    }));
  }
  $('#calPrev').addEventListener('click', () => { st.month = new Date(st.month.getFullYear(), st.month.getMonth() - 1, 1); renderCal(); });
  $('#calNext').addEventListener('click', () => { st.month = new Date(st.month.getFullYear(), st.month.getMonth() + 1, 1); renderCal(); });

  /* ---- 3: čas ---- */
  async function loadSlots() {
    go(3);
    $('#slotDate').textContent = nice(st.date).replace(/^./, c => c.toUpperCase()) + ' – ' + cfg.services[st.service].name;
    const box = $('#slots'); box.innerHTML = '<div class="empty-msg" style="grid-column:1/-1">Načítavam…</div>';
    try {
      const r = await fetch('api/availability.php?service=' + encodeURIComponent(st.service) + '&date=' + st.date);
      const j = await r.json();
      if (!j.slots || !j.slots.length) { box.innerHTML = '<div class="empty-msg" style="grid-column:1/-1">Tento deň už nie sú voľné termíny. Vyberte prosím iný dátum.</div>'; return; }
      box.innerHTML = j.slots.map(t => '<button type="button" class="slot" data-t="' + t + '">' + t + '</button>').join('');
      $$('.slot', box).forEach(b => b.addEventListener('click', () => {
        st.time = b.dataset.t; summary();
        $$('.slot', box).forEach(x => x.classList.toggle('sel', x === b));
        setTimeout(() => go(4), 280);
      }));
    } catch (e) { box.innerHTML = '<div class="empty-msg" style="grid-column:1/-1">Chyba pri načítaní termínov.</div>'; }
  }

  /* ---- 4: odoslanie ---- */
  const form = $('#bkForm');
  const rules = {
    name: v => v.trim().length >= 3 || 'Zadajte meno a priezvisko.',
    phone: v => /^\+?[0-9 ()\-]{9,18}$/.test(v.trim()) || 'Zadajte platné telefónne číslo.',
    email: v => !v.trim() || /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(v.trim()) || 'E-mail nie je platný.',
  };
  function setErr(name, msg) {
    const f = form.elements[name]; if (!f) return;
    const field = f.closest('.field'); field.classList.toggle('bad', !!msg);
    const e = $('.err', field); if (e) e.textContent = msg || '';
  }
  function validate() {
    let ok = true;
    Object.keys(rules).forEach(k => { const r = rules[k](form.elements[k].value); setErr(k, r === true ? '' : r); if (r !== true) ok = false; });
    if (!form.elements.consent.checked) { setErr('consent', 'Je potrebný súhlas so spracovaním údajov.'); ok = false; } else setErr('consent', '');
    return ok;
  }
  form.addEventListener('submit', async e => {
    e.preventDefault();
    const msg = $('#formMsg'); msg.className = ''; msg.textContent = '';
    if (!validate()) return;
    const btn = $('#bkSubmit'); btn.disabled = true;
    const body = {
      service: st.service, date: st.date, time: st.time,
      name: form.elements.name.value, phone: form.elements.phone.value, email: form.elements.email.value,
      note: form.elements.note.value, website: form.elements.website.value, consent: form.elements.consent.checked,
      elapsed: Math.round((Date.now() - st.t0) / 1000),
    };
    try {
      const r = await fetch('api/book.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body) });
      const j = await r.json();
      if (j.ok) return done(j);
      msg.className = 'alert bad'; msg.textContent = j.error || 'Rezerváciu sa nepodarilo uložiť.';
      if (j.fields) Object.entries(j.fields).forEach(([k, v]) => setErr(k, v));
      if (j.conflict) { cache = {}; setTimeout(() => loadSlots(), 1600); }
    } catch (err) { msg.className = 'alert bad'; msg.textContent = 'Chyba spojenia. Skúste to prosím znova alebo nám zavolajte na ' + cfg.phone + '.'; }
    btn.disabled = false;
  });

  function done(j) {
    $('#doneText').textContent = j.status === 'confirmed' ? cfg.texts.confirmed : cfg.texts.pending;
    $('#doneBox').innerHTML = '<b>' + j.service + '</b><br>' + j.when + ' (do ' + j.end + ')<br>' + form.elements.name.value;
    $('#doneNote').textContent = cfg.texts.note.replace('{phone}', cfg.phone);
    go('done');
    confetti();
  }
  function confetti() {
    if (matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    const colors = ['#f4a3b0', '#e3708a', '#98b496', '#d3e1d1', '#c93f66'];
    for (let i = 0; i < 70; i++) {
      const c = document.createElement('i'); c.className = 'confetti';
      c.style.left = Math.random() * 100 + 'vw'; c.style.background = colors[i % colors.length];
      c.style.animationDuration = (2 + Math.random() * 2.5) + 's'; c.style.animationDelay = Math.random() * .6 + 's';
      c.style.borderRadius = Math.random() > .5 ? '50%' : '2px';
      document.body.appendChild(c); setTimeout(() => c.remove(), 5500);
    }
  }

  /* ---- Štart (predvolená služba z ?service=) ---- */
  const pre = new URLSearchParams(location.search).get('service');
  summary();
  if (pre && cfg.services[pre]) {
    st.service = pre; $$('.svc-opt').forEach(x => x.classList.toggle('sel', x.dataset.id === pre));
    summary(); initMonth(); go(2);
  } else go(1);
})();
