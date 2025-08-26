document.getElementById("check-all").addEventListener("change", function () {
    let checked = this.checked;
    document
        .querySelectorAll(".check-item")
        .forEach((cb) => (cb.checked = checked));
});
