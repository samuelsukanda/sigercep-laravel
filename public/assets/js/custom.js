// Datatables
$(document).ready(function () {
    if (!$.fn.DataTable.isDataTable("#datatable")) {
        var table = $("#datatable").DataTable({
            dom: "Bfrtip",
            buttons: ["colvis"],
            responsive: true,
            language: {
                paginate: {
                    previous: "<<",
                    next: ">>",
                },
            },
        });

        // Filter Unit
        $("#filter-unit").on("change", function () {
            table.column(1).search(this.value).draw();
        });

        // Filter Jenis SPO
        $("#filter-jenis").on("change", function () {
            table.column(2).search(this.value).draw();
        });

        // Filter Tanggal
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            const startDateStr = $("#start_date").val();
            const endDateStr = $("#end_date").val();
            const dateColumnIndex = parseInt(
                $("#datatable").data("date-column")
            );

            if (!startDateStr && !endDateStr) return true;

            const createdAtRaw = data[dateColumnIndex];
            const parts = createdAtRaw.split(" ");
            const tgl = parts[0].padStart(2, "0");
            const bulan = parts[1];
            const tahun = parts[2];

            const monthMap = {
                Januari: "01",
                Februari: "02",
                Maret: "03",
                April: "04",
                Mei: "05",
                Juni: "06",
                Juli: "07",
                Agustus: "08",
                September: "09",
                Oktober: "10",
                November: "11",
                Desember: "12",
            };

            const createdDateStr = `${tahun}-${monthMap[bulan]}-${tgl}`;

            if (startDateStr && createdDateStr < startDateStr) return false;
            if (endDateStr && createdDateStr > endDateStr) return false;

            return true;
        });

        $("#start_date, #end_date").on("change", function () {
            table.draw();
        });
    }
});

// Select2
$(document).ready(function () {
    $("#unit").select2({
        placeholder: "Pilih Unit",
        allowClear: true,
    });

    $("#lantai").select2({
        placeholder: "Pilih Lantai",
        allowClear: true,
    });

    $("#filter-unit").select2({
        placeholder: "Pilih Unit",
        allowClear: true,
    });

    $("#filter-jenis").select2({
        placeholder: "Pilih Jenis",
        allowClear: true,
    });

    $("#toner").select2({
        placeholder: "Pilih Toner",
        allowClear: true,
    });

    $("#jenis_spo").select2({
        placeholder: "Pilih Jenis SPO",
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

    $("#satuan").select2({
        placeholder: "Pilih Ukuran",
        allowClear: true,
    });

    $("#detik").select2({
        placeholder: "Pilih Durasi",
        allowClear: true,
    });

    $("#menit").select2({
        placeholder: "Pilih Durasi",
        allowClear: true,
    });

    $("#perawat").select2({
        placeholder: "Pilih Perawat",
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
