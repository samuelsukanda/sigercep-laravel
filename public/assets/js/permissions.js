(function () {
    "use strict";

    // Inject styles to override global button style issues with SweetAlert
    const swalStyle = document.createElement('style');
    swalStyle.innerHTML = `
      .swal2-container .swal2-styled.swal2-confirm {
        background-color: #ef4444 !important;
        color: #ffffff !important;
        border: none !important;
        transition: background-color 0.2s !important;
      }
      .swal2-container .swal2-styled.swal2-confirm:hover {
        background-color: #dc2626 !important;
      }
      .swal2-container .swal2-styled.swal2-cancel {
        background-color: #6b7280 !important;
        color: #ffffff !important;
        border: none !important;
        transition: background-color 0.2s !important;
      }
      .swal2-container .swal2-styled.swal2-cancel:hover {
        background-color: #4b5563 !important;
      }
    `;
    document.head.appendChild(swalStyle);

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute("content") : "";

    /* Data */
    var permissionsData = JSON.parse(
        document.getElementById("permissionsData").textContent,
    );
    var usersData = JSON.parse(
        document.getElementById("usersData").textContent,
    );
    var activePerm = null;
    var activeTab = "list";
    var ruleCounter = 1;

    /* ───── MODAL Tambah Permission ───── */
    window.openPermModal = function (menu, action) {
        var menuSel = document.getElementById("inp-menu");
        var actionSel = document.getElementById("inp-action");

        if (menu) {
            if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
                window.jQuery(menuSel).val(menu).trigger("change");
            } else {
                menuSel.value = menu;
            }
        }
        if (action) {
            actionSel.value = action;
        }

        document.getElementById("permModalOverlay").classList.add("open");
    };

    window.closePermModal = function () {
        document.getElementById("permModalOverlay").classList.remove("open");
    };

    window.handleModalOverlayClick = function (e) {
        if (e.target.id === "permModalOverlay") {
            closePermModal();
        }
    };

    /* ───── SUBMIT ADD RULE ───── */
    window.submitAddRule = function () {
        const userSel = document.getElementById("pf-user");

        if (!userSel || !userSel.value) {
            toastr.warning("Pilih user terlebih dahulu!", "Info");
            return;
        }

        fillAddRuleHidden();
        document.getElementById("addRuleForm").submit();
    };

    /* ───── SIDE PANEL ───── */
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

    /* ───── RENDER RULE LIST ───── */
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

                if (r.unit)
                    parts.push(
                        '<span class="rule-meta-chip"><i class="fas fa-building"></i>' +
                            esc(r.unit.toUpperCase()) +
                            "</span>",
                    );

                if (r.jabatan)
                    parts.push(
                        '<span class="rule-meta-chip"><i class="fas fa-user-tie"></i>' +
                            esc(r.jabatan.toUpperCase()) +
                            "</span>",
                    );

                var icon = r.unit
                    ? "fa-building"
                    : r.jabatan
                    ? "fa-user-tie"
                    : "fa-user";

                var formattedName = r.name ? formatUserName(r.name) : "";

                var nameRow = formattedName
                    ? '<div class="rule-name-main"><i class="fas fa-user" style="font-size:10px;margin-right:3px;color:var(--muted)"></i>' +
                    esc(formattedName) +
                    "</div>"
                    : "";

                // Encode data untuk onclick attribute
                var safeName = escAttr(r.name || "");
                var safeUnit = escAttr(r.unit || "");
                var safeJabatan = escAttr(r.jabatan || "");

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
                    // Tombol aksi — edit & delete
                    '<div class="rule-item-actions">' +
                    // Tombol Edit
                    '<button type="button" class="btn-rule-edit" ' +
                    'onclick="openEditRuleModal(' +
                    r.id +
                    ", '" +
                    safeName +
                    "', '" +
                    safeUnit +
                    "', '" +
                    safeJabatan +
                    "')\" " +
                    'title="Edit rule">' +
                    '<i class="fas fa-pen"></i>' +
                    "</button>" +
                    // Tombol Delete
                    '<form action="/permissions/delete-rule/' +
                    r.id +
                    '" method="POST" ' +
                    'class="form-delete-rule" data-name="' +
                    esc(formattedName || "rule ini") +
                    '" ' +
                    'style="display:inline;margin:0">' +
                    '<input type="hidden" name="_token" value="' +
                    csrfToken +
                    '">' +
                    '<input type="hidden" name="_method" value="DELETE">' +
                    '<button type="button" class="btn-rule-delete btn-delete-rule" title="Hapus rule">' +
                    '<i class="fas fa-trash-can"></i>' +
                    "</button>" +
                    "</form>" +
                    "</div>" + // .rule-item-actions
                    "</div>" // .rule-list-item
                );
            })
            .join("");
    }

    /* ───── MODAL EDIT RULE ───── */
    window.openEditRuleModal = function (ruleId, name, unit, jabatan) {
        document.getElementById("editRuleId").value = ruleId;
        document.getElementById("edit-name").value = formatUserName(name || "");
        document.getElementById("edit-unit").value = formatUserName(unit || "");
        document.getElementById("edit-jabatan").value =
            formatUserName(jabatan || "");

        // Reset state tombol
        var btn = document.getElementById("btnSaveEditRule");
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-floppy-disk"></i> Simpan Perubahan';

        document.getElementById("editRuleModalOverlay").classList.add("open");
    };

    window.closeEditRuleModal = function () {
        document
            .getElementById("editRuleModalOverlay")
            .classList.remove("open");
    };

    window.handleEditRuleOverlayClick = function (e) {
        if (e.target.id === "editRuleModalOverlay") closeEditRuleModal();
    };

    window.submitEditRule = async function () {
        const ruleId = document.getElementById("editRuleId").value;
        const name = canonicalUserField(
            document.getElementById("edit-name").value.trim(),
            "name",
        );
        const unit = canonicalUserField(
            document.getElementById("edit-unit").value.trim(),
            "unit",
        );
        const jabatan = canonicalUserField(
            document.getElementById("edit-jabatan").value.trim(),
            "jabatan",
        );

        if (!name && !unit && !jabatan) {
            toastr.warning("Isi minimal satu field!", "Info");
            return;
        }

        const btn = document.getElementById("btnSaveEditRule");
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

        try {
            const res = await fetch(window.updateRuleUrl + "/" + ruleId, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    Accept: "application/json",
                },
                body: JSON.stringify({ name, unit, jabatan }),
            });

            const data = await res.json();

            if (!res.ok) {
                toastr.error(
                    data.error || "Gagal menyimpan perubahan.",
                    "Error",
                );
                btn.disabled = false;
                btn.innerHTML =
                    '<i class="fas fa-floppy-disk"></i> Simpan Perubahan';
                return;
            }

            // Update data in-memory
            for (var i = 0; i < permissionsData.length; i++) {
                var perm = permissionsData[i];
                for (var j = 0; j < perm.rules.length; j++) {
                    if (perm.rules[j].id == ruleId) {
                        perm.rules[j].name = name;
                        perm.rules[j].unit = unit;
                        perm.rules[j].jabatan = jabatan;
                        break;
                    }
                }
            }

            // Update activePerm juga agar renderRuleList pakai data baru
            if (activePerm) {
                for (var k = 0; k < activePerm.rules.length; k++) {
                    if (activePerm.rules[k].id == ruleId) {
                        activePerm.rules[k].name = name;
                        activePerm.rules[k].unit = unit;
                        activePerm.rules[k].jabatan = jabatan;
                        break;
                    }
                }
            }

            toastr.success("Rule berhasil diperbarui!", "Berhasil");
            closeEditRuleModal();

            // Re-render panel tanpa reload
            renderRuleList();
        } catch (err) {
            toastr.error("Terjadi kesalahan. Coba lagi.", "Error");
            btn.disabled = false;
            btn.innerHTML =
                '<i class="fas fa-floppy-disk"></i> Simpan Perubahan';
        }
    };

    /* ───── HELPERS ───── */
    function formatUserName(name) {
        if (!name) return "";
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

        return name
            .split(" ")
            .map(function (word) {
                return (
                    word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
                );
            })
            .join(" ");
    }

    window.formatUserName = formatUserName;

    /* Ubah nilai tampilan kembali ke format raw DB (raden.ibnu, dll).
       Cocokkan dengan usersData per field, abaikan spasi/titik/underscore. */
    function normalizeKey(s) {
        return String(s || "").toLowerCase().replace(/[^a-z0-9]/g, "");
    }

    function canonicalUserField(input, field) {
        var key = normalizeKey(input);
        if (!key) return input;
        var hit = usersData.find(function (u) {
            return normalizeKey(u[field]) === key;
        });
        return hit ? hit[field] : input;
    }

    // Escape untuk innerHTML
    function esc(str) {
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;");
    }

    // Escape untuk nilai di dalam single-quote onclick attribute
    function escAttr(str) {
        return String(str).replace(/\\/g, "\\\\").replace(/'/g, "\\'");
    }

    function clearAddForm() {
        var sel = document.getElementById("pf-user");
        if (sel) {
            if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
                window.jQuery(sel).val(null).trigger("change");
            } else {
                sel.value = "";
            }
        }
        document.getElementById("pf-unit").value = "";
        document.getElementById("pf-jabatan").value = "";
        document.getElementById("pf-name").value = "";
    }

    /* ───── USER SELECT (auto-fill name/unit/jabatan) ───── */
    function fillAddRuleHidden() {
        var sel = document.getElementById("pf-user");
        if (!sel) return;
        var user = usersData.find(function (u) {
            return String(u.id) === String(sel.value);
        });
        document.getElementById("pf-name").value = user ? user.name : "";
        document.getElementById("pf-unit").value = user ? user.unit : "";
        document.getElementById("pf-jabatan").value = user ? user.jabatan : "";
    }

    function initAddRuleUserSelect() {
        var sel = document.getElementById("pf-user");
        if (!sel) return;
        var $sel = window.jQuery(sel);
        if (window.jQuery.fn.select2) {
            $sel.select2({
                width: "100%",
                placeholder: "Pilih User...",
                allowClear: true,
            });
        }
        $sel.on("change", fillAddRuleHidden);
    }

    window.initRuleUserSelect = function (select) {
        var $sel = window.jQuery(select);
        if (window.jQuery.fn.select2) {
            $sel.select2({
                width: "100%",
                placeholder: "Pilih User...",
                allowClear: true,
            });
        }
        $sel.on("change", function () {
            var val = window.jQuery(this).val();
            var row = window.jQuery(this).closest("[data-rule-row]");
            var user = usersData.find(function (u) {
                return String(u.id) === String(val);
            });
            row.find('input[name$="[name]"]').val(user ? user.name : "");
            row.find('input[name$="[unit]"]').val(user ? user.unit : "");
            row.find('input[name$="[jabatan]"]').val(user ? user.jabatan : "");
        });
    };

    /* ───── MENU SELECT (modal Tambah Permission) ───── */
    function initMenuSelect() {
        var $menu = window.jQuery("#inp-menu");
        if (window.jQuery.fn.select2) {
            $menu.select2({
                width: "100%",
                placeholder: "Pilih Menu...",
                allowClear: false,
            });
        }
    }

    /* ───── DROPDOWN "Kelola" per menu ───── */
    function closeAllDropdowns() {
        document
            .querySelectorAll(".perm-menu-dropdown-panel")
            .forEach(function (p) {
                p.style.display = "none";
            });
    }

    window.toggleMenuDropdown = function (btn) {
        var dd = btn.closest(".perm-menu-dropdown");
        if (!dd) return;
        var panel = dd.querySelector(".perm-menu-dropdown-panel");
        var isOpen = panel.style.display === "block";
        closeAllDropdowns();
        if (!isOpen) {
            panel.style.display = "block";
            panel.style.top = "calc(100% + 6px)";
            panel.style.bottom = "auto";
            // Jika dropdown nyaris keluar viewport bawah, buka ke atas
            var r = panel.getBoundingClientRect();
            if (window.innerHeight - r.bottom < 10) {
                panel.style.top = "auto";
                panel.style.bottom = "calc(100% + 6px)";
            }
        }
    };

    document.addEventListener("click", function (e) {
        if (!e.target.closest(".perm-menu-dropdown")) {
            closeAllDropdowns();
        }
    });

    /* ───── INIT ───── */
    function onReady(fn) {
        if (document.readyState !== "loading") fn();
        else document.addEventListener("DOMContentLoaded", fn);
    }

    onReady(function () {
        if (!window.jQuery) return;
        initMenuSelect();
        initAddRuleUserSelect();
        document
            .querySelectorAll(".rule-user-select")
            .forEach(initRuleUserSelect);

        // Guard duplikat menu+action sebelum simpan
        document
            .getElementById("permForm")
            .addEventListener("submit", function (e) {
                var menu = document.getElementById("inp-menu").value.trim();
                var action = document.getElementById("inp-action").value;
                var dup = permissionsData.some(function (p) {
                    return p.menu === menu && p.action === action;
                });
                if (dup) {
                    e.preventDefault();
                    toastr.error(
                        "Permission untuk menu '" +
                            menu +
                            "' dengan action '" +
                            action +
                            "' sudah ada!",
                        "Error",
                    );
                }
            });
    });
})();

/* ───── Alert Delete Global ───── */
document.addEventListener("DOMContentLoaded", function () {
    // Success alert after delete redirect
    var params = new URLSearchParams(window.location.search);
    if (params.get("deleted") === "1") {
        params.delete("deleted");
        var qs = params.toString();
        var newUrl = window.location.pathname + (qs ? "?" + qs : "") + window.location.hash;
        history.replaceState(null, "", newUrl);
        Swal.fire({
            title: "Terhapus!",
            text: "Data berhasil dihapus.",
            icon: "success",
            confirmButtonColor: "var(--accent)",
            customClass: { confirmButton: "btn-swal-success" },
            confirmButtonText: "OK"
        });
    }
});
document.addEventListener("click", function (e) {
    const btn = e.target.closest(".btn-delete-rule, .btn-delete-trigger");
    if (!btn) return;

    e.preventDefault();
    const form = btn.closest("form");
    const name = form.dataset.name || "data ini";

    Swal.fire({
        title: "Konfirmasi Hapus",
        html: `Yakin ingin menghapus <b>${name}</b>?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
