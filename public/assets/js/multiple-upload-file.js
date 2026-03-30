// Multiple Upload FIle JS
let selectedFiles = [];

const fileInput = document.getElementById("fileInput");
const previewContainer = document.getElementById("previewContainer");
const uploadContainer = document.getElementById("uploadContainer");
const btnTambah = document.getElementById("btnTambah");

uploadContainer.addEventListener("click", () => fileInput.click());
btnTambah.addEventListener("click", () => fileInput.click());

fileInput.addEventListener("change", function (e) {
    const files = Array.from(e.target.files);

    files.forEach((file) => {
        selectedFiles.push(file);
    });

    renderPreview();
});

function renderPreview() {
    previewContainer.innerHTML = "";

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();

        reader.onload = function (e) {
            const div = document.createElement("div");
            div.classList.add("preview-item");

            div.innerHTML = `
                <img src="${e.target.result}" />
                <button class="btn-remove" onclick="removeFile(${index})">✕</button>
            `;

            previewContainer.appendChild(div);
        };

        reader.readAsDataURL(file);
    });

    updateInputFiles();
}

function removeFile(index) {
    Swal.fire({
        title: "Hapus file?",
        text: "File akan dihapus",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Ya, hapus!",
    }).then((result) => {
        if (result.isConfirmed) {
            selectedFiles.splice(index, 1);
            renderPreview();
        }
    });
}

function updateInputFiles() {
    const dataTransfer = new DataTransfer();

    selectedFiles.forEach((file) => {
        dataTransfer.items.add(file);
    });

    fileInput.files = dataTransfer.files;
}
