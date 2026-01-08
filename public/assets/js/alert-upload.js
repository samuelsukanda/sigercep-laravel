// Laravel Toaster
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("form");

    const requiredFiles = ["foto", "foto_barang", "dokumentasi", "file_pdf", "file_spo"];

    form.addEventListener("submit", function (e) {
        let valid = true;

        requiredFiles.forEach(function (name) {
            const input = document.getElementById(`${name}-upload`);
            if (input && input.files.length === 0) {
                valid = false;
                toastr.error(
                    `${name.replace(
                        "_",
                        " "
                    )} harus diisi sebelum mengirim form!`,
                    "Error"
                );
            }
        });

        if (!valid) {
            e.preventDefault();
        }
    });

    // Toastr konfigurasi
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: "5000",
    };
});
