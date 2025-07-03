// Desain Grafis JS
document.addEventListener("DOMContentLoaded", function () {
    const formUkuran = document.getElementById("form-ukuran");
    const formDurasi = document.getElementById("form-durasi");

    // Inisialisasi sembunyi dulu
    formUkuran.style.display = "none";
    formDurasi.style.display = "none";

    const desainRadios = document.querySelectorAll('input[name="desain"]');

    function handleDesainChange(value) {
        value = value.toLowerCase();
        if (["foto", "spanduk", "brosur", "roll up banner"].includes(value)) {
            formUkuran.style.display = "flex";
            formDurasi.style.display = "none";
        } else if (value === "video") {
            formUkuran.style.display = "none";
            formDurasi.style.display = "flex";
        } else {
            formUkuran.style.display = "none";
            formDurasi.style.display = "none";
        }
    }

    // Event listener untuk setiap radio button
    desainRadios.forEach((radio) => {
        radio.addEventListener("change", function () {
            handleDesainChange(this.value);
        });
    });

    const selected = document.querySelector('input[name="desain"]:checked');
    if (selected) {
        handleDesainChange(selected.value);
    }
});
