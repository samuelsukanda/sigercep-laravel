// Datatables
$(document).ready(function () {
    $("#datatable").DataTable({
        language: {
            paginate: {
                previous: "<<",
                next: ">>",
            },
        },
    });
});

// Select2
$(document).ready(function () {
    $("#unit").select2({
        placeholder: "Pilih Unit",
        allowClear: true,
    });

    $("#tujuan_unit").select2({
        placeholder: "Pilih Tujuan Unit",
        allowClear: true,
    });

    $("#status").select2({
        placeholder: "Pilih Status",
        allowClear: true,
    });
});

// Flatpickr
flatpickr("#tanggal", {
    dateFormat: "Y-m-d",
    allowInput: true,
});
