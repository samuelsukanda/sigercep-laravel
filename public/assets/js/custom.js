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

    $("#jenis_kendaraan").select2({
        placeholder: "Pilih Jenis Kendaraan",
        allowClear: true,
    });

    $("#jumlah_penumpang").select2({
        placeholder: "Pilih Jumlah Penumpang",
        allowClear: true,
    });

    $("#waktu_tempuh").select2({
        placeholder: "Pilih  Waktu Tempuh",
        allowClear: true,
    });

    $("#jarak_tempuh").select2({
        placeholder: "Pilih  Jarak Tempuh",
        allowClear: true,
    });

    $("#jenis_layanan").select2({
        placeholder: "Pilih Jenis Layanan",
        allowClear: true,
    });

    $("#tim").select2({
        placeholder: "Pilih Tim",
        allowClear: true,
    });

    $("#dampak").select2({
        placeholder: "Pilih Nilai",
        allowClear: true,
    });

    $("#kemungkinan").select2({
        placeholder: "Pilih Nilai",
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

flatpickr("#jam_berangkat", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
});

flatpickr("#jam_pulang", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
});
