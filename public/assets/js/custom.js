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

    $("#ruang").select2({
        placeholder: "Pilih Ruang",
        allowClear: true,
    });

    $("#approval").select2({
        placeholder: "Pilih Approval",
        allowClear: true,
    });
});

// Flatpickr
flatpickr("#tanggal", {
    dateFormat: "Y-m-d",
    allowInput: true,
});

flatpickr("#jam", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
});

flatpickr("#jam_mulai", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
});

flatpickr("#jam_selesai", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
});
