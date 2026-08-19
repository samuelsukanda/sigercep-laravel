window.SIGERCEP = window.SIGERCEP || {};
(function () {
    'use strict';

    function applyDarkUI() {
        var on = document.documentElement.classList.contains('dark');
        var lbl = document.getElementById('darkModeLabel');
        if (lbl) {
            lbl.innerHTML = on
                ? '<i class="fas fa-sun mr-2"></i> Mode Terang'
                : '<i class="fas fa-moon mr-2"></i> Mode Gelap';
        }
    }

    SIGERCEP.toggleDark = function () {
        var root = document.documentElement;
        root.classList.toggle('dark');
        try {
            localStorage.setItem('sigercep-dark', root.classList.contains('dark') ? 'on' : 'off');
        } catch (e) { }
        applyDarkUI();
    };

    SIGERCEP.setTheme = function (name) {
        document.documentElement.setAttribute('data-theme', name);
        try {
            localStorage.setItem('sigercep-theme', name);
        } catch (e) { }
    };

    document.addEventListener('DOMContentLoaded', applyDarkUI);
})();