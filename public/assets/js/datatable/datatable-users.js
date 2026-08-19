// public/assets/js/datatable/datatable-users.js
$(document).ready(function () {
    $("#userTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: window.location.href,
            type: "GET",
            data: function (d) {},
        },
        responsive: true,
        pageLength: 10,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Semua"],
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya",
            },
            zeroRecords: "Tidak ada data yang ditemukan",
            emptyTable: "Tidak ada data tersedia",
        },
        columns: [
            {
                data: "name",
                render: function (data, type, row) {
                    return `
                        <div class="flex items-center gap-3">
                            <div class="um-avatar ${row.avatar_class}" style="margin-right: 5px;">
                                ${row.initials}
                            </div>
                            <div>
                                <div class="um-name">${data}</div>
                                <div class="um-jabatan">${row.jabatan}</div>
                            </div>
                        </div>`;
                },
            },
            {
                data: "nik",
                render: function (data) {
                    return `<span class="um-name">${data}</span>`;
                },
            },
            {
                data: "username",
                render: function (data) {
                    return `<span class="um-username-pill">${data}</span>`;
                },
            },
            {
                data: "status_karyawan",
                render: function (data) {
                    return `<span class="um-status um-status--active">
                                <span class="um-status-dot"></span>
                                ${data}
                            </span>`;
                },
            },
            {
                data: "created_at_formatted",
                render: function (data, type, row) {
                    if (type === "sort" || type === "type") {
                        return row.created_at_timestamp;
                    }
                    return `
                        <div class="um-meta">
                            <span class="um-meta-label">ID:</span>
                            <span class="um-meta-val">${row.user_id}</span>
                        </div>
                        <div class="um-meta">
                            <span class="um-meta-label">Dibuat:</span>
                            <span class="um-meta-val">${data}</span>
                        </div>`;
                },
            },
        ],

        initComplete: function () {
            $(this.api().table().container()).addClass(
                "datatable-custom-wrapper",
            );
        },

        order: [[0, "asc"]],
    });
});