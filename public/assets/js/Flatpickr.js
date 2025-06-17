// Flatpickr
const input = document.getElementById("foto-upload");
const fileNameDisplay = document.getElementById("file-name");

input.addEventListener("change", function () {
    if (input.files.length > 0) {
        fileNameDisplay.textContent = input.files[0].name;
    } else {
        fileNameDisplay.textContent = "No File Choosen";
    }
});

flatpickr("#tanggal", {
    dateFormat: "Y-m-d",
    allowInput: true,
});
