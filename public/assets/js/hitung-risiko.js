function hitungNilaiRisiko() {
    var dampak = document.getElementById("dampak").value;
    var kemungkinan = document.getElementById("kemungkinan").value;

    dampak = parseFloat(dampak);
    kemungkinan = parseFloat(kemungkinan);

    if (!isNaN(dampak) && !isNaN(kemungkinan)) {
        var nilaiResiko = dampak * kemungkinan;
        document.getElementById("nilai").value = nilaiResiko;
    } else {
        document.getElementById("nilai").value = "";
    }
}
