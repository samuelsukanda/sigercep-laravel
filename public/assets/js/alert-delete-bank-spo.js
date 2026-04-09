document.addEventListener("click", function (e) {
    if (e.target.closest(".delete-button")) {
        const button = e.target.closest(".delete-button");
        const form = button.closest("form");
        const confirmMessage =
            button.dataset.confirm || "Yakin ingin menghapus?";

        toastr.clear();

        toastr.options = {
            closeButton: false,
            progressBar: true,
            timeOut: 0,
            extendedTimeOut: 0,
            positionClass: "toast-top-center",
            tapToDismiss: false,
            onShown: function () {
                const toastEl = document.querySelector(".toast");
                const actionArea = document.createElement("div");
                actionArea.className = "mt-2 text-center";
                actionArea.innerHTML = `
                    <button class="btn btn-sm btn-danger me-2" id="confirmDelete">Ya, Hapus</button>
                    <button class="btn btn-sm btn-secondary" id="cancelDelete">Batal</button>
                `;
                toastEl.appendChild(actionArea);

                document.getElementById("confirmDelete").onclick = () => {
                    form.submit();
                };

                document.getElementById("cancelDelete").onclick = () => {
                    toastr.remove();
                };
            },
        };

        toastr.warning(confirmMessage, "Konfirmasi Hapus");
    }
});
