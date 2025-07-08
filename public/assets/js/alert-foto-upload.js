// Laravel Toaster
document.getElementById("form").addEventListener("submit", function (e) {
    const fileInput = document.getElementById("foto-upload");
    const file = fileInput.files[0];

    if (!file) {
        e.preventDefault();
        toastr.error("Foto harus diisi sebelum mengirim form!", "Error");
        return false;
    }
});

toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "5000",
};
