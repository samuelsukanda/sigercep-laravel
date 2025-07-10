document.addEventListener("DOMContentLoaded", function () {
    const canvas = document.getElementById("signature-pad");
    const form = document.getElementById("form");
    const clearBtn = document.getElementById("clear");
    const undoBtn = document.getElementById("undo");
    const tandaTanganInput = document.getElementById("tanda_tangan");
    const editBtn = document.getElementById("edit-signature");
    const cancelBtn = document.getElementById("cancel-edit");
    const signatureContainer = document.getElementById("signature-container");
    const currentSignatureContainer = document.getElementById(
        "current-signature-container"
    );
    const previewImg = document.getElementById("signature-preview");

    if (!canvas || !form || !tandaTanganInput) {
        console.error("Elemen penting tidak ditemukan");
        return;
    }

    let signaturePad;
    let originalData = null;
    let isEditing = false;

    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        const width = canvas.offsetWidth || 400;
        const height = canvas.offsetHeight || 200;

        canvas.width = width * ratio;
        canvas.height = height * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
    }

    function initPad() {
        resizeCanvas();
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: "rgb(255,255,255)",
        });
    }

    function loadExistingSignature() {
        if (!previewImg) return;

        const img = new Image();
        img.crossOrigin = "anonymous";
        img.onload = function () {
            const ctx = canvas.getContext("2d");
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            const scale = Math.min(
                canvas.width / img.width,
                canvas.height / img.height
            );
            const sw = img.width * scale;
            const sh = img.height * scale;
            const x = (canvas.width - sw) / 2;
            const y = (canvas.height - sh) / 2;

            ctx.drawImage(img, x, y, sw, sh);
            originalData = signaturePad.toData();
            signaturePad._isEmpty = false;
        };
        img.src = previewImg.src;
    }

    if (editBtn) {
        editBtn.addEventListener("click", function () {
            isEditing = true;
            signatureContainer.classList.remove("hidden");
            currentSignatureContainer?.classList.add("hidden");

            setTimeout(() => {
                initPad();
                loadExistingSignature();
            }, 100);
        });
    } else {
        setTimeout(() => {
            initPad();
        }, 100);
    }

    if (clearBtn) {
        clearBtn.addEventListener("click", () => signaturePad?.clear());
    }

    if (undoBtn) {
        undoBtn.addEventListener("click", () => {
            const data = signaturePad?.toData();
            if (data?.length) {
                data.pop();
                signaturePad.fromData(data);
            }
        });
    }

    if (cancelBtn) {
        cancelBtn.addEventListener("click", () => {
            isEditing = false;
            signatureContainer.classList.add("hidden");
            currentSignatureContainer?.classList.remove("hidden");
            tandaTanganInput.value = "";
        });
    }

    form.addEventListener("submit", function (e) {
        if (signaturePad.isEmpty()) {
            e.preventDefault();
            toastr.error(
                "Tanda tangan belum diisi. Silakan isi tanda tangan terlebih dahulu.",
                "Validasi Gagal"
            );
            return;
        }

        const signatureData = signaturePad.toDataURL("image/png");
        tandaTanganInput.value = signatureData;
    });
});
