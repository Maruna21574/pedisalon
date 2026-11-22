/* Súhlas s cookies a externými službami (Google Mapy). Voľba sa ukladá len do úložiska prehliadača (kľúč pz-consent). */
(function () {
  'use strict';
  var KEY = 'pz-consent', VER = 1;
  var bar = document.getElementById('cookieBar');

  function read() {
    try { var v = JSON.parse(localStorage.getItem(KEY)); if (v && v.v === VER) return v; } catch (e) {}
    return null;
  }
  function write(maps) {
    try { localStorage.setItem(KEY, JSON.stringify({ v: VER, maps: !!maps, t: Date.now() })); } catch (e) {}
  }

  /* ---- Mapa: načíta sa až po súhlase ---- */
  function loadMaps() {
    document.querySelectorAll('[data-map-src]').forEach(function (box) {
      if (box.querySelector('iframe')) return;
      var f = document.createElement('iframe');
      f.src = box.getAttribute('data-map-src');
      f.title = 'Mapa';
      f.loading = 'lazy';
      f.referrerPolicy = 'no-referrer-when-downgrade';
      box.innerHTML = '';
      box.appendChild(f);
      box.classList.add('loaded');
    });
  }

  /* ---- Lišta ---- */
  function hide() { if (bar) bar.hidden = true; }
  function show(panel) {
    if (!bar) return;
    var c = read();
    var chk = bar.querySelector('[name="maps"]');
    if (chk) chk.checked = !!(c && c.maps);
    bar.querySelector('.cb-details').hidden = !panel;
    bar.hidden = false;
  }
  function choose(maps) {
    write(maps);
    hide();
    if (maps) loadMaps();
  }

  if (bar) {
    bar.querySelector('[data-cb="all"]').addEventListener('click', function () { choose(true); });
    bar.querySelector('[data-cb="necessary"]').addEventListener('click', function () { choose(false); });
    bar.querySelector('[data-cb="custom"]').addEventListener('click', function () { show(true); });
    bar.querySelector('[data-cb="save"]').addEventListener('click', function () { choose(bar.querySelector('[name="maps"]').checked); });
  }
  document.querySelectorAll('[data-cookie-settings]').forEach(function (b) {
    b.addEventListener('click', function (e) { e.preventDefault(); show(true); });
  });
  document.querySelectorAll('[data-map-allow]').forEach(function (b) {
    b.addEventListener('click', function () { var c = read(); write(true); loadMaps(); if (!c) hide(); });
  });

  var saved = read();
  if (saved) { if (saved.maps) loadMaps(); }
  else show(false);
})();
