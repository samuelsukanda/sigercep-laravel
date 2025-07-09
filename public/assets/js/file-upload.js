// File Upload
document.querySelectorAll('input[type="file"]').forEach(function (input) {
    input.addEventListener("change", function () {
        const fileNameDisplay = this.closest('div').querySelector('#file-name');
        if (this.files.length > 0) {
            fileNameDisplay.textContent = this.files[0].name;
        } else {
            fileNameDisplay.textContent = "No File Chosen";
        }
    });
});
