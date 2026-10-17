
document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menuToggle');
    const navMenu = document.getElementById('navMenu');

    if (!menuToggle || !navMenu) {
        return;
    }

    menuToggle.addEventListener('click', function () {
        const isOpen = navMenu.classList.toggle('open');

        menuToggle.setAttribute('aria-expanded', isOpen);
        menuToggle.setAttribute(
            'aria-label',
            isOpen ? 'Tutup menu' : 'Buka menu'
        );

        menuToggle.textContent = isOpen ? '✕' : '☰';
    });

    navMenu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
            navMenu.classList.remove('open');
            menuToggle.setAttribute('aria-expanded', 'false');
            menuToggle.setAttribute('aria-label', 'Buka menu');
            menuToggle.textContent = '☰';
        });
    });
});
