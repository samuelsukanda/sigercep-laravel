// Toggle Dropdown
function toggleDropdown(element) {
    const dropdownMenu = element.nextElementSibling;
    const iconChevron = element.querySelector("i.fas.fa-chevron-down");

    if (
        dropdownMenu.style.maxHeight &&
        dropdownMenu.style.maxHeight !== "0px"
    ) {
        // close dropdown
        dropdownMenu.style.maxHeight = "0";
        dropdownMenu.style.opacity = "0";
        iconChevron.style.transform = "rotate(0deg)";
    } else {
        // open dropdown
        dropdownMenu.style.maxHeight = dropdownMenu.scrollHeight + "px";
        dropdownMenu.style.opacity = "1";
        iconChevron.style.transform = "rotate(180deg)";
    }
}

window.addEventListener("click", function (e) {
    const dropdowns = document.querySelectorAll("[dropdown-menu]");
    dropdowns.forEach((menu) => {
        const trigger = menu.previousElementSibling;
        if (!trigger.contains(e.target) && !menu.contains(e.target)) {
            menu.style.maxHeight = "0";
            menu.style.opacity = "0";
            const iconChevron = trigger.querySelector("i.fas.fa-chevron-down");
            if (iconChevron) iconChevron.style.transform = "rotate(0deg)";
        }
    });
});