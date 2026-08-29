// Toggle Dropdown
function toggleDropdown(element) {
    var menu = element.nextElementSibling;
    var icon = element.querySelector("i.fas.fa-chevron-down");
    var isOpen = menu.style.maxHeight && menu.style.maxHeight !== "0px";

    menu.style.maxHeight = isOpen ? "0" : menu.scrollHeight + "px";
    menu.style.opacity = isOpen ? "0" : "1";
    if (icon) icon.style.transform = isOpen ? "rotate(0deg)" : "rotate(180deg)";
    if (window.saveSidebarState) window.saveSidebarState();
}

window.addEventListener("click", function (e) {
    document.querySelectorAll("[dropdown-menu]").forEach(function (menu) {
        var trigger = menu.previousElementSibling;
        if (!trigger.contains(e.target) && !menu.contains(e.target)) {
            menu.style.maxHeight = "0";
            menu.style.opacity = "0";
            var icon = trigger.querySelector("i.fas.fa-chevron-down");
            if (icon) icon.style.transform = "rotate(0deg)";
        }
    });
});

(function () {
    var STORAGE_KEY = "sidebarStateV2";

    function normalizePath(path) {
        return (path || "/").replace(/\/+$/, "").toLowerCase() || "/";
    }

    function sidebar() {
        return document.querySelector("#sidebar-scroll");
    }

    function saveSidebarState() {
        var node = sidebar();
        if (!node) return;
        var openMenus = [];
        node.querySelectorAll("[dropdown-menu]").forEach(function (menu, index) {
            if (menu.style.maxHeight && menu.style.maxHeight !== "0px") openMenus.push(index);
        });
        sessionStorage.setItem(STORAGE_KEY, JSON.stringify({
            scrollTop: node.scrollTop,
            openMenus: openMenus
        }));
    }

    window.saveSidebarState = saveSidebarState;

    function restoreSidebar() {
        var node = sidebar();
        if (!node) return;
        var state;
        try {
            state = JSON.parse(sessionStorage.getItem(STORAGE_KEY) || "{}");
        } catch (e) {
            state = {};
        }

        var currentPath = normalizePath(window.location.pathname);
        var activeLink = null;
        node.querySelectorAll("a[href]").forEach(function (link) {
            if (normalizePath(link.pathname) !== currentPath) return;
            activeLink = link;
            link.classList.add("sidebar-link-active");
            link.style.backgroundColor = "var(--accent-soft)";
            link.style.color = "var(--accent)";
            link.style.boxShadow = "inset 3px 0 0 var(--accent)";
            link.style.fontWeight = "600";
        });

        node.querySelectorAll("[dropdown-menu]").forEach(function (menu, index) {
            if (!activeLink || !menu.contains(activeLink)) {
                if (!state.openMenus || state.openMenus.indexOf(index) === -1) return;
            }
            var icon = menu.previousElementSibling.querySelector("i.fas.fa-chevron-down");
            // Restore state instantly after navigation; manual toggles keep animation.
            menu.style.transition = "none";
            menu.style.maxHeight = menu.scrollHeight + "px";
            menu.style.opacity = "1";
            if (icon) icon.style.transform = "rotate(180deg)";
            requestAnimationFrame(function () {
                menu.style.transition = "";
            });
        });

        var scrollTop = Number(state.scrollTop);
        if (!Number.isFinite(scrollTop)) return;
        function applyScroll() {
            node.scrollTop = scrollTop;
        }
        requestAnimationFrame(function () {
            applyScroll();
            requestAnimationFrame(applyScroll);
            setTimeout(applyScroll, 100);
            setTimeout(applyScroll, 300);
        });
    }

    document.addEventListener("mousedown", function (e) {
        if (e.target.closest("#sidebar-scroll a[href]") && window.saveSidebarState) {
            window.saveSidebarState();
        }
    });

    document.addEventListener("click", function (e) {
        if (e.target.closest("#sidebar-scroll a[href]") && window.saveSidebarState) {
            window.saveSidebarState();
        }
    });

    window.addEventListener("pagehide", saveSidebarState);
    window.addEventListener("beforeunload", saveSidebarState);
    document.addEventListener("DOMContentLoaded", function () {
        var node = sidebar();
        if (node) node.addEventListener("scroll", saveSidebarState, { passive: true });
    });

    window.addEventListener("pageshow", restoreSidebar);
    document.addEventListener("DOMContentLoaded", restoreSidebar);
})();
