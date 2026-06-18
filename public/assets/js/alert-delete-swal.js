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

document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("click", function (e) {
        const button = e.target.closest(".delete-button");
        if (!button) return;

        e.preventDefault();
        const confirmMessage = button.dataset.confirm || "Yakin ingin menghapus data ini?";
        const form = button.closest("form");

        if (!form) return;

        Swal.fire({
            title: "Konfirmasi Hapus",
            text: confirmMessage,
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
});
