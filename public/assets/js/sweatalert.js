// SweetAlert
document.getElementById("form").addEventListener("submit", function (e) {
    const fileInput = document.getElementById("foto-upload");
    const file = fileInput.files[0];

    if (!file) {
        e.preventDefault();
        Swal.fire({
            icon: "error",
            title: "Error...",
            text: "Foto harus diisi sebelum mengirim form!",
            confirmButtonText: "OK",
        });
    }
});