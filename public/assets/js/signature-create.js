document.addEventListener("DOMContentLoaded", function () {
    const canvas = document.getElementById("signature-pad");
    const form = document.getElementById("form");
    const clearBtn = document.getElementById("clear");
    const undoBtn = document.getElementById("undo");
    const tandaTanganInput = document.getElementById("tanda_tangan");

    if (!canvas || !form || !clearBtn || !undoBtn || !tandaTanganInput) {
        console.error("Elemen tidak ditemukan. Pastikan ID elemen sudah benar dan HTML sudah diload.");
        return;
    }

    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        const width = canvas.offsetWidth;
        const height = canvas.offsetHeight;

        canvas.width = width * ratio;
        canvas.height = height * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
    }

    window.addEventListener("resize", resizeCanvas);
    resizeCanvas();

    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: "rgb(255,255,255)",
    });

    clearBtn.addEventListener("click", function () {
        signaturePad.clear();
    });

    undoBtn.addEventListener("click", function () {
        const data = signaturePad.toData();
        if (data.length) {
            data.pop();
            signaturePad.fromData(data);
        }
    });

    form.addEventListener("submit", function (e) {
        if (signaturePad.isEmpty()) {
            e.preventDefault();
            toastr.error("Tanda tangan belum diisi. Silakan isi tanda tangan terlebih dahulu.", "Error");
            return;
        }

        const signatureData = signaturePad.toDataURL("image/png");
        tandaTanganInput.value = signatureData;
    });
});
