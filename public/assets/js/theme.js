window.SIGERCEP = window.SIGERCEP || {};
(function () {
    'use strict';

    function setActiveSwatch() {
        var current = document.documentElement.getAttribute('data-theme') || 'violet';
        document.querySelectorAll('.theme-swatch').forEach(function (btn) {
            btn.classList.toggle('active', btn.getAttribute('data-theme') === current);
        });
    }

    SIGERCEP.toggleDark = function () {
        var root = document.documentElement;
        root.classList.toggle('dark');
        try {
            localStorage.setItem('sigercep-dark', root.classList.contains('dark') ? 'on' : 'off');
        } catch (e) { }
    };

    SIGERCEP.setTheme = function (name) {
        document.documentElement.setAttribute('data-theme', name);
        try {
            localStorage.setItem('sigercep-theme', name);
        } catch (e) { }
        setActiveSwatch();
    };

    document.addEventListener('DOMContentLoaded', setActiveSwatch);
})();