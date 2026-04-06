(function () {
    "use strict";

    /* ── Data ── */
    var permissionsData = JSON.parse(
        document.getElementById("permissionsData").textContent,
    );
    var activePerm = null;
    var activeTab = "list";
    var ruleCounter = 1;

    /* ═══ SIDE PANEL ═══ */
    window.openRulePanel = function (permId, menu, action) {
        activePerm = permissionsData.find(function (p) {
            return p.id === permId;
        });
        if (!activePerm) return;

        document.getElementById("panelTitle").textContent =
            "Kelola Rules — " + menu;
        document.getElementById("panelMenuChip").textContent = menu;
        document.getElementById("panelActionBadge").textContent =
            action.toUpperCase();

        var addRuleForm = document.getElementById("addRuleForm");
        addRuleForm.action = "/permissions/" + permId + "/add-rule";

        switchTab("list");
        renderRuleList();

        document.getElementById("rulePanelOverlay").classList.add("open");
        document.getElementById("rulePanel").classList.add("open");
    };

    window.closeRulePanel = function () {
        document.getElementById("rulePanelOverlay").classList.remove("open");
        document.getElementById("rulePanel").classList.remove("open");
        activePerm = null;
        clearAddForm();
    };

    window.switchTab = function (tab) {
        activeTab = tab;
        document
            .getElementById("tabBtnList")
            .classList.toggle("active", tab === "list");
        document
            .getElementById("tabBtnAdd")
            .classList.toggle("active", tab === "add");
        document
            .getElementById("sectionList")
            .classList.toggle("active", tab === "list");
        document
            .getElementById("sectionAdd")
            .classList.toggle("active", tab === "add");
        document.getElementById("btnSaveRule").style.display =
            tab === "add" ? "inline-flex" : "none";
    };

    function renderRuleList() {
        var container = document.getElementById("ruleListContainer");
        var rules = activePerm ? activePerm.rules : [];

        document.getElementById("tabCountBadge").textContent = rules.length;

        if (rules.length === 0) {
            container.innerHTML =
                '<div class="rule-list-empty">' +
                '<i class="fas fa-users-slash"></i>' +
                "<p>Belum ada rule.<br>Semua user dapat mengakses menu ini.</p>" +
                "</div>";
            return;
        }

        container.innerHTML = rules
            .map(function (r) {
                var parts = [];

                // ✅ UNIT - HURUF BESAR SEMUA
                if (r.unit)
                    parts.push(
                        '<span class="rule-meta-chip"><i class="fas fa-building"></i>' +
                            esc(r.unit.toUpperCase()) +
                            "</span>",
                    ); // <-- tambah .toUpperCase()

                // ✅ JABATAN - HURUF BESAR SEMUA
                if (r.jabatan)
                    parts.push(
                        '<span class="rule-meta-chip"><i class="fas fa-user-tie"></i>' +
                            esc(r.jabatan.toUpperCase()) +
                            "</span>",
                    ); // <-- tambah .toUpperCase()

                var icon = r.unit
                    ? "fa-building"
                    : r.jabatan
                      ? "fa-user-tie"
                      : "fa-user";

                // ✅ NAMA USER - Format: raden.ibnu -> Ibnu Raden
                var formattedName = "";
                if (r.name) {
                    formattedName = formatUserName(r.name);
                }

                var nameRow = formattedName
                    ? '<div class="rule-name-main"><i class="fas fa-user" style="font-size:10px;margin-right:3px;color:var(--muted)"></i>' +
                      esc(formattedName) +
                      "</div>"
                    : "";

                return (
                    '<div class="rule-list-item" id="rule-item-' +
                    r.id +
                    '">' +
                    '<div class="rule-list-icon"><i class="fas ' +
                    icon +
                    '"></i></div>' +
                    '<div class="rule-list-meta">' +
                    (parts.length
                        ? '<div class="rule-list-meta-row">' +
                          parts.join('<span class="rule-meta-sep">·</span>') +
                          "</div>"
                        : "") +
                    nameRow +
                    (!parts.length && !r.name
                        ? '<span style="font-size:.72rem;color:var(--hint);font-style:italic">Rule tanpa detail</span>'
                        : "") +
                    "</div>" +
                    '<form action="/permissions/delete-rule/' +
                    r.id +
                    '" method="POST" style="display:inline;margin:0">' +
                    '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                    '<input type="hidden" name="_method" value="DELETE">' +
                    '<button type="submit" class="btn-rule-delete" title="Hapus rule" ' +
                    "onclick=\"return confirm('Hapus rule ini?')\">" +
                    '<i class="fas fa-trash-can"></i>' +
                    "</button>" +
                    "</form>" +
                    "</div>"
                );
            })
            .join("");
    }

    // ✅ TAMBAHKAN FUNGSI INI untuk format nama user
    function formatUserName(name) {
        if (!name) return "";

        // Jika nama mengandung titik (format: raden.ibnu)
        if (name.includes(".")) {
            return name
                .split(".")
                .map(function (part) {
                    return (
                        part.charAt(0).toUpperCase() +
                        part.slice(1).toLowerCase()
                    );
                })
                .join(" ");
        }

        // Jika sudah dalam format normal, tetap capitalize tiap kata
        return name
            .split(" ")
            .map(function (word) {
                return (
                    word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
                );
            })
            .join(" ");
    }

    window.submitAddRule = function () {
        var unit = document.getElementById("pf-unit").value.trim();
        var jabatan = document.getElementById("pf-jabatan").value.trim();
        var name = document.getElementById("pf-name").value.trim();
        if (!unit && !jabatan && !name) {
            alert("Isi minimal satu field (Unit, Jabatan, atau Nama User).");
            return;
        }
        document.getElementById("addRuleForm").submit();
    };

    function clearAddForm() {
        document.getElementById("pf-unit").value = "";
        document.getElementById("pf-jabatan").value = "";
        document.getElementById("pf-name").value = "";
    }

    function esc(str) {
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;");
    }

    /* ═══ MODAL Tambah Permission ═══ */
    window.openPermModal = function () {
        document.getElementById("permModalOverlay").classList.add("open");
        setTimeout(function () {
            document.getElementById("inp-menu").focus();
        }, 80);
    };
    window.closePermModal = function () {
        document.getElementById("permModalOverlay").classList.remove("open");
        resetPermForm();
    };
    window.handleModalOverlayClick = function (e) {
        if (e.target === document.getElementById("permModalOverlay"))
            closePermModal();
    };

    function resetPermForm() {
        document.getElementById("permForm").reset();
        document.getElementById("modalRulesRows").innerHTML =
            '<div class="modal-rule-row">' +
            '<input type="text" name="rules[0][unit]"    placeholder="Unit">' +
            '<input type="text" name="rules[0][jabatan]" placeholder="Jabatan">' +
            '<input type="text" name="rules[0][name]"    placeholder="Nama user">' +
            "<div></div>" +
            "</div>";
        ruleCounter = 1;
    }
    window.addModalRuleRow = function () {
        var c = document.getElementById("modalRulesRows");
        var d = document.createElement("div");
        d.className = "modal-rule-row";
        d.innerHTML =
            '<input type="text" name="rules[' +
            ruleCounter +
            '][unit]"    placeholder="Unit">' +
            '<input type="text" name="rules[' +
            ruleCounter +
            '][jabatan]" placeholder="Jabatan">' +
            '<input type="text" name="rules[' +
            ruleCounter +
            '][name]"    placeholder="Nama user">' +
            '<button type="button" class="btn-rm-row-modal" ' +
            "onclick=\"this.closest('.modal-rule-row').remove()\">" +
            '<i class="fas fa-xmark"></i>' +
            "</button>";
        c.appendChild(d);
        d.querySelector("input").focus();
        ruleCounter++;
    };

    /* ═══ ESC ═══ */
    document.addEventListener("keydown", function (e) {
        if (e.key !== "Escape") return;
        if (document.getElementById("rulePanel").classList.contains("open")) {
            closeRulePanel();
            return;
        }
        if (
            document
                .getElementById("permModalOverlay")
                .classList.contains("open")
        ) {
            closePermModal();
        }
    });

    /* ═══ Auto-dismiss alerts ═══ */
    document.querySelectorAll(".perm-alert").forEach(function (el) {
        setTimeout(function () {
            el.style.transition = "opacity .5s";
            el.style.opacity = "0";
            setTimeout(function () {
                el.remove();
            }, 500);
        }, 4000);
    });
})();

(function () {
    "use strict";

    // Fitur pencarian menu
    const searchInput = document.getElementById("searchMenuInput");
    const clearBtn = document.getElementById("clearSearchBtn");
    const tableRows = document.querySelectorAll(".perm-table tbody tr");
    const tableBody = document.querySelector(".perm-table tbody");

    // Fungsi untuk melakukan pencarian
    function filterMenu() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let hasVisibleRows = false;

        if (!tableRows.length) return;

        tableRows.forEach((row) => {
            // Cari cell menu (biasanya cell pertama)
            const menuCell = row.querySelector("td:first-child");
            if (!menuCell) return;

            const menuText = menuCell.textContent.toLowerCase();
            const menuSpan = menuCell.querySelector(".menu-chip");

            if (searchTerm === "") {
                // Tampilkan semua row
                row.classList.remove("hidden-row");
                // Hapus highlight
                removeHighlight(menuCell);
                hasVisibleRows = true;
            } else if (menuText.includes(searchTerm)) {
                // Tampilkan row yang match
                row.classList.remove("hidden-row");
                hasVisibleRows = true;
                // Tambah highlight
                addHighlight(menuCell, searchTerm);
            } else {
                // Sembunyikan row yang tidak match
                row.classList.add("hidden-row");
                removeHighlight(menuCell);
            }
        });

        // Tampilkan pesan jika tidak ada hasil
        showEmptyResult(!hasVisibleRows && searchTerm !== "");

        // Tampilkan/sembunyikan tombol clear
        clearBtn.style.display = searchTerm !== "" ? "block" : "none";
    }

    // Fungsi untuk menambah highlight
    function addHighlight(element, searchTerm) {
        const originalText = element.textContent;
        const menuChip = element.querySelector(".menu-chip");
        if (!menuChip) return;

        const text = menuChip.textContent;
        const regex = new RegExp(`(${escapeRegex(searchTerm)})`, "gi");
        const highlightedHtml = text.replace(
            regex,
            '<span class="search-highlight">$1</span>',
        );

        if (highlightedHtml !== text) {
            menuChip.innerHTML = highlightedHtml;
            menuChip.setAttribute("data-original-text", text);
        }
    }

    // Fungsi untuk menghapus highlight
    function removeHighlight(element) {
        const menuChip = element.querySelector(".menu-chip");
        if (menuChip && menuChip.hasAttribute("data-original-text")) {
            menuChip.textContent = menuChip.getAttribute("data-original-text");
            menuChip.removeAttribute("data-original-text");
        } else if (
            menuChip &&
            menuChip.innerHTML.includes("search-highlight")
        ) {
            menuChip.textContent = menuChip.textContent;
        }
    }

    // Fungsi escape regex
    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
    }

    // Fungsi menampilkan pesan empty
    function showEmptyResult(show) {
        let emptyRow = document.querySelector(
            ".perm-table tbody tr.search-empty-row",
        );

        if (show) {
            if (!emptyRow) {
                emptyRow = document.createElement("tr");
                emptyRow.className = "search-empty-row";
                emptyRow.innerHTML = `
                            <td colspan="4">
                                <div class="search-empty">
                                    <i class="fas fa-search"></i>
                                    <p>Tidak ada menu yang cocok dengan "<strong id="searchTermDisplay"></strong>"</p>
                                </div>
                            </td>
                        `;
                tableBody.appendChild(emptyRow);
            }
            document.getElementById("searchTermDisplay").textContent =
                searchInput.value;
            emptyRow.style.display = "";
        } else {
            if (emptyRow) {
                emptyRow.style.display = "none";
            }
        }
    }

    // Event listener
    if (searchInput) {
        searchInput.addEventListener("input", filterMenu);
        searchInput.addEventListener("keyup", function (e) {
            if (e.key === "Escape") {
                searchInput.value = "";
                filterMenu();
                searchInput.blur();
            }
        });
    }

    if (clearBtn) {
        clearBtn.addEventListener("click", function () {
            searchInput.value = "";
            filterMenu();
            searchInput.focus();
        });
    }

    // Inisialisasi (pastikan tidak ada row yang tersembunyi)
    filterMenu();
})();
