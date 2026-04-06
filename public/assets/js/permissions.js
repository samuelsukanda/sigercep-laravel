(function () {
    "use strict";

    var ruleCounter = 1;

    /* ─── Open / Close ─── */
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

    window.handleOverlayClick = function (e) {
        if (e.target === document.getElementById("permModalOverlay")) {
            closePermModal();
        }
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

    /* ─── Add rule row ─── */
    window.addModalRuleRow = function () {
        var container = document.getElementById("modalRulesRows");
        var row = document.createElement("div");
        row.className = "modal-rule-row";
        row.innerHTML =
            '<input type="text" name="rules[' +
            ruleCounter +
            '][unit]"    placeholder="Unit">' +
            '<input type="text" name="rules[' +
            ruleCounter +
            '][jabatan]" placeholder="Jabatan">' +
            '<input type="text" name="rules[' +
            ruleCounter +
            '][name]"    placeholder="Nama user">' +
            '<button type="button" class="btn-rm-row-modal" title="Hapus baris" ' +
            "onclick=\"this.closest('.modal-rule-row').remove()\">" +
            '<i class="fas fa-xmark"></i>' +
            "</button>";
        container.appendChild(row);
        row.querySelector("input").focus();
        ruleCounter++;
    };

    /* ─── ESC to close ─── */
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            closePermModal();
        }
    });

    /* ─── Auto-dismiss alerts ─── */
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
