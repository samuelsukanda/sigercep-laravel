document.addEventListener("DOMContentLoaded", function () {
        const deleteButtons = document.querySelectorAll(".delete-button");

        deleteButtons.forEach(button => {
            button.addEventListener("click", function () {
                const confirmMessage = button.dataset.confirm || "Yakin ingin menghapus?";
                const form = button.closest("form");

                toastr.clear();

                toastr.options = {
                    "closeButton": false,
                    "progressBar": true,
                    "timeOut": 0,
                    "extendedTimeOut": 0,
                    "positionClass": "toast-top-center",
                    "onclick": null,
                    "tapToDismiss": false,
                    "onShown": function () {
                        const toastEl = document.querySelector('.toast');
                        const actionArea = document.createElement('div');
                        actionArea.className = 'mt-2 text-center';
                        actionArea.innerHTML = `
                            <button class="btn btn-sm btn-danger me-2" id="confirmDelete">Ya, Hapus</button>
                            <button class="btn btn-sm btn-secondary" id="cancelDelete">Batal</button>
                        `;
                        toastEl.appendChild(actionArea);

                        document.getElementById('confirmDelete').addEventListener('click', () => {
                            form.submit();
                        });

                        document.getElementById('cancelDelete').addEventListener('click', () => {
                            toastr.remove();
                        });
                    }
                };

                toastr.warning(confirmMessage, "Konfirmasi Hapus");
            });
        });
    });