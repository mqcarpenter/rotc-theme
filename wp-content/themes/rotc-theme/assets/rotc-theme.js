(function () {
  var toggle = document.querySelector('.rotc-nav-toggle');
  var menu = document.querySelector('.rotc-nav-menu');
  if (!toggle || !menu) return;
  toggle.addEventListener('click', function () {
    var open = menu.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
})();
