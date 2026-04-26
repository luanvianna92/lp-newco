// Adiciona sombra ao header ao rolar a página.
(function () {
    var header = document.getElementById('rd-header');
    if (!header) return;
    var update = function () {
        if (window.scrollY > 8) {
            header.classList.add('is-scrolled');
        } else {
            header.classList.remove('is-scrolled');
        }
    };
    update();
    window.addEventListener('scroll', update, { passive: true });
})();
