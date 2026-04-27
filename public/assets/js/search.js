(function () {
    "use strict";

    // DOM Elements
    const searchInput = document.getElementById("searchMenuInput");
    const clearBtn = document.getElementById("clearSearchBtn");
    const searchInfo = document.getElementById("searchInfo");
    const searchResultCount = document.getElementById("searchResultCount");
    const filterBadge = document.getElementById("filterBadge");
    const filterCountSpan = document.getElementById("filterCount");
    const tableBody = document.getElementById("permissionsTableBody");
    const totalPermissionsSpan = document.getElementById("totalPermissions");

    let currentSearchTerm = "";

    // Function to highlight text
    function highlightText(text, searchTerm) {
        if (!searchTerm || searchTerm.trim() === "") {
            return text;
        }

        const regex = new RegExp(`(${escapeRegex(searchTerm)})`, "gi");
        return text.replace(regex, '<span class="search-highlight">$1</span>');
    }

    // Escape regex special characters
    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
    }

    // Main search function
    function performSearch() {
        const searchTerm = searchInput.value.trim();
        currentSearchTerm = searchTerm;

        const rows = tableBody.querySelectorAll("tr:not(#emptyRow)");
        let visibleCount = 0;

        if (searchTerm === "") {
            // Show all rows
            rows.forEach((row) => {
                row.classList.remove("hidden-row");
                visibleCount++;
                // Remove highlights
                const menuCell = row.querySelector(".menu-text");
                if (menuCell && menuCell.originalText) {
                    menuCell.innerHTML = menuCell.originalText;
                    menuCell.originalText = null;
                } else if (menuCell) {
                    menuCell.originalText = menuCell.innerHTML;
                    menuCell.innerHTML = menuCell.originalText;
                }
            });

            // Hide clear button and search info
            clearBtn.style.display = "none";
            searchInfo.style.display = "none";
            filterBadge.style.display = "none";
        } else {
            // Filter rows
            const lowerSearchTerm = searchTerm.toLowerCase();

            rows.forEach((row) => {
                const menuText = row.querySelector(".menu-text");
                if (!menuText) return;

                const originalText =
                    menuText.originalText || menuText.innerHTML;
                menuText.originalText = originalText;

                const menuName =
                    row.getAttribute("data-menu") || originalText.toLowerCase();

                if (menuName.includes(lowerSearchTerm)) {
                    row.classList.remove("hidden-row");
                    visibleCount++;
                    // Apply highlight
                    const plainText = originalText;
                    menuText.innerHTML = highlightText(plainText, searchTerm);
                } else {
                    row.classList.add("hidden-row");
                    menuText.innerHTML = originalText;
                }
            });

            // Show clear button and search info
            clearBtn.style.display = "flex";
            searchInfo.style.display = "block";
            filterBadge.style.display = "flex";

            // Update search info text
            const totalRows = rows.length;
            searchResultCount.innerHTML = `Menampilkan ${visibleCount} dari ${totalRows} menu`;
            filterCountSpan.textContent = visibleCount;
        }

        // Handle empty state display
        const hasVisibleRows = visibleCount > 0;
        const emptyRow = document.getElementById("emptyRow");
        const originalEmptyRow = document.querySelector(
            "#permissionsTableBody #emptyRow",
        );

        if (searchTerm !== "" && visibleCount === 0) {
            // Show empty search result message if no existing empty row
            if (!document.getElementById("searchEmptyRow")) {
                const newEmptyRow = document.createElement("tr");
                newEmptyRow.id = "searchEmptyRow";
                newEmptyRow.innerHTML = `
                            <td colspan="4">
                                <div class="search-empty-result">
                                    <i class="fas fa-search"></i>
                                    <h3>Tidak ada menu yang ditemukan</h3>
                                    <p>Tidak ada menu dengan nama "${escapeHtml(searchTerm)}"</p>
                                </div>
                            </td>
                        `;
                tableBody.appendChild(newEmptyRow);
            }
            if (originalEmptyRow) originalEmptyRow.style.display = "none";
        } else {
            const searchEmptyRow = document.getElementById("searchEmptyRow");
            if (searchEmptyRow) searchEmptyRow.remove();
            if (originalEmptyRow)
                originalEmptyRow.style.display =
                    visibleCount === 0 && searchTerm === "" ? "" : "none";
        }
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    // Clear search
    function clearSearch() {
        searchInput.value = "";
        performSearch();
        searchInput.focus();
    }

    // Debounce function for better performance
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Event listeners
    if (searchInput) {
        searchInput.addEventListener("input", debounce(performSearch, 300));
        searchInput.addEventListener("keypress", (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                performSearch();
            }
        });
    }

    if (clearBtn) {
        clearBtn.addEventListener("click", clearSearch);
    }

    // Initialize - store original menu texts and add data-menu attributes
    function initializeSearchData() {
        const rows = tableBody.querySelectorAll("tr:not(#emptyRow)");
        rows.forEach((row) => {
            const menuCell = row.querySelector(".menu-text");
            if (menuCell) {
                const menuText = menuCell.textContent.trim();
                row.setAttribute("data-menu", menuText.toLowerCase());
                menuCell.originalText = menuCell.innerHTML;
            }
        });
    }

    // Call initialization when DOM is ready
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initializeSearchData);
    } else {
        initializeSearchData();
    }
})();
