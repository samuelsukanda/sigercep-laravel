document.addEventListener("DOMContentLoaded", function () {
    const logoutTrigger = document.querySelector("[dropdown-logout-trigger]");
    const logoutMenu = document.querySelector("[dropdown-logout-menu]");

    logoutTrigger.addEventListener("click", function (e) {
        e.stopPropagation(); // Hindari tertutup oleh klik global

        const isOpen = logoutMenu.classList.contains("opacity-100");

        // Tutup dulu semua menu floating jika perlu
        document.querySelectorAll("[dropdown-logout-menu]").forEach((menu) => {
            menu.classList.add("opacity-0", "pointer-events-none");
            menu.classList.remove("opacity-100", "pointer-events-auto");
        });

        // Toggle menu ini
        if (!isOpen) {
            logoutMenu.classList.remove("opacity-0", "pointer-events-none");
            logoutMenu.classList.add("opacity-100", "pointer-events-auto");
        }
    });

    // Klik di luar = tutup menu
    window.addEventListener("click", function () {
        logoutMenu.classList.add("opacity-0", "pointer-events-none");
        logoutMenu.classList.remove("opacity-100", "pointer-events-auto");
    });
});
