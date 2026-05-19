(function () {
    "use strict";

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute("content") : "";

    /* Data */
    var permissionsData = JSON.parse(
        document.getElementById("permissionsData").textContent,
    );
    var activePerm = null;
    var activeTab = "list";
    var ruleCounter = 1;

    /* ───── MODAL Tambah Permission ───── */
    window.openPermModal = function () {
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
        const form = document.getElementById("addRuleForm");

        const unit = document.getElementById("pf-unit").value.trim();
        const jabatan = document.getElementById("pf-jabatan").value.trim();
        const name = document.getElementById("pf-name").value.trim();

        if (!unit && !jabatan && !name) {
            toastr.warning("Isi minimal satu field!", "Info");
            return;
        }

        form.submit();
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
        document.getElementById("edit-name").value = name || "";
        document.getElementById("edit-unit").value = unit || "";
        document.getElementById("edit-jabatan").value = jabatan || "";

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
        const name = document.getElementById("edit-name").value.trim();
        const unit = document.getElementById("edit-unit").value.trim();
        const jabatan = document.getElementById("edit-jabatan").value.trim();

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
        document.getElementById("pf-unit").value = "";
        document.getElementById("pf-jabatan").value = "";
        document.getElementById("pf-name").value = "";
    }
})();

/* ───── Alert Delete Global ───── */
document.addEventListener("click", function (e) {
    const btn = e.target.closest(".btn-delete-rule, .btn-delete-trigger");
    if (!btn) return;

    const form = btn.closest("form");
    const name = form.dataset.name || "data ini";

    toastr.clear();

    toastr.options = {
        closeButton: false,
        progressBar: true,
        timeOut: 0,
        extendedTimeOut: 0,
        positionClass: "toast-top-center",
        tapToDismiss: false,
        newestOnTop: true,
        onShown: function () {
            const toastEl = document.querySelector(".toast");
            if (!toastEl || toastEl.querySelector(".confirm-area")) return;

            const actionArea = document.createElement("div");
            actionArea.className = "mt-2 text-center confirm-area";
            actionArea.innerHTML = `
                <button class="btn btn-sm btn-danger me-2 btn-confirm-delete">
                    Ya, Hapus
                </button>
                <button class="btn btn-sm btn-secondary btn-cancel-delete">
                    Batal
                </button>
            `;

            toastEl.appendChild(actionArea);

            toastEl.querySelector(".btn-confirm-delete").onclick = () => {
                form.submit();
            };

            toastEl.querySelector(".btn-cancel-delete").onclick = () => {
                toastr.remove();
            };
        },
    };

    toastr.warning(`Yakin hapus <b>${name}</b>?`, "Konfirmasi Hapus");
});
