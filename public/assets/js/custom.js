// Select2
$(document).ready(function () {
    if ($("#unit").is("select")) {
        $("#unit").select2({
            placeholder: "Pilih Unit",
            allowClear: true,
        });
    }

    $("#unit_asal").select2({
        placeholder: "Pilih Unit Asal",
        allowClear: true,
    });

    $("#unit_tujuan").select2({
        placeholder: "Pilih Unit Tujuan",
        allowClear: true,
    });

    if ($("#lantai").is("select")) {
        $("#lantai").select2({
            placeholder: "Pilih Lantai",
            allowClear: true,
        });
    }

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

    if ($("#dampak").is("select")) {
        $("#dampak").select2({
            placeholder: "Pilih Nilai",
            allowClear: true,
        });
    }

    $("#kemungkinan").select2({
        placeholder: "Pilih Nilai",
        allowClear: true,
    });

    $("#analisis_tingkat").select2({
        placeholder: "Pilih Tingkat",
        allowClear: true,
    });

    $("#mitigasi_tingkat").select2({
        placeholder: "Pilih Tingkat",
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

    $("#kelompok_umur").select2({
        placeholder: "Pilih Kelompok Umur",
        allowClear: true,
    });

    $("#jenis_kelamin").select2({
        placeholder: "Pilih Jenis Kelamin",
        allowClear: true,
    });

    $("#penanggung_jawab").select2({
        placeholder: "Pilih Penanggung Jawab",
        allowClear: true,
    });

    $("#jenis_kejadian").select2({
        placeholder: "Pilih Jenis Kejadian",
        allowClear: true,
    });

    $("#jenis_insiden").select2({
        placeholder: "Pilih Jenis Insiden",
        allowClear: true,
    });

    $("#insiden_pasien").select2({
        placeholder: "Pilih Insiden Pasien",
        allowClear: true,
    });

    $("#jenis_spesialisasi_pasien").select2({
        placeholder: "Pilih Jenis Spesialisasi Pasien",
        allowClear: true,
    });

    $("#akibat_insiden").select2({
        placeholder: "Pilih Jenis Akibat Insiden",
        allowClear: true,
    });

    $("#tindakan_dilakukan_oleh").select2({
        placeholder: "Pilih Jenis Tindakan Dilakukan Oleh",
        allowClear: true,
    });

    $("#kejadian_serupa").select2({
        placeholder: "Pilih Jenis Kejadian Serupa",
        allowClear: true,
    });

    $("#grading_risiko").select2({
        placeholder: "Pilih Jenis Grading Risiko",
        allowClear: true,
    });

    $("#jenis_dokumen").select2({
        placeholder: "Pilih Jenis Jenis Dokumen",
        allowClear: true,
    });

    $("#permintaan_fitur").select2({
        placeholder: "Pilih Permintaan Fitur",
        allowClear: true,
    });

    $("#permintaan_pengajuan").select2({
        placeholder: "Pilih Jenis Permintaan Pengajuan",
        allowClear: true,
    });

    $("#kategori_pengajuan").select2({
        placeholder: "Pilih Jenis Kategori Pengajuan",
        allowClear: true,
    });

    $("#alasan_pengajuan").select2({
        placeholder: "Pilih Jenis Alasan Pengajuan",
        allowClear: true,
    });

    $("#kategori_laporan").select2({
        placeholder: "Pilih Jenis Kategori Laporan",
        allowClear: true,
    });

    $("#urgency").select2({
        placeholder: "Pilih Jenis Urgensi",
        allowClear: true,
    });

    $("#category").select2({
        placeholder: "Pilih Jenis Kategori",
        allowClear: true,
    });

    $("#approval_status").select2({
        placeholder: "Pilih Status Approval",
        allowClear: true,
    });
});

// Flatpickr
flatpickr("#tanggal", {
    dateFormat: "Y-m-d",
    allowInput: false,
});

flatpickr("#periode_dari", {
    dateFormat: "Y-m-d",
    allowInput: false,
});

flatpickr("#periode_sampai", {
    dateFormat: "Y-m-d",
    allowInput: false,
});

flatpickr("#tanggal_lahir", {
    dateFormat: "Y-m-d",
    allowInput: false,
});

flatpickr("#tanggal_masuk_rs", {
    dateFormat: "Y-m-d",
    allowInput: false,
});

flatpickr("#tanggal_kejadian", {
    dateFormat: "Y-m-d",
    allowInput: false,
});

flatpickr("#tanggal_pengajuan", {
    dateFormat: "Y-m-d",
    allowInput: false,
});

flatpickr("#estimated_completion", {
    enableTime: true,
    dateFormat: "d-m-Y H:i",
    time_24hr: true,
    allowInput: false,
    defaultDate: new Date() 
});

flatpickr("#waktu_kejadian", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
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
