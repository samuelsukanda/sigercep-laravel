// Datatable Summary
$(document).ready(function () {
    $("#ticketTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: window.location.href,
            type: "GET",
            data: function (d) {
                d.periode_dari = $("input[name=periode_dari]").val();
                d.periode_sampai = $("input[name=periode_sampai]").val();
                d.kategori = $("select[name=kategori]").val();
                d.status_tiket = $("select[name=status_tiket]").val();
                d.status_approval = $("select[name=status_approval]").val();
            },
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
                data: "ticket_number",
                render: function (data) {
                    return `<span class="font-semibold text-blue-600">${data}</span>`;
                },
            },
            {
                data: "created_at_formatted",
                render: function (data, type, row) {
                    if (type === "sort" || type === "type") {
                        return row.created_at_timestamp;
                    }
                    return `<span class="text-gray-500 whitespace-nowrap text-s">${data}</span>`;
                },
            },
            { data: "user_name" },
            { data: "user_unit" },
            { data: "category" },
            { data: "urgency" },
            {
                data: "description",
                render: function (data) {
                    return `<span class="text-gray-500 max-w-[200px] truncate text-s">${data}</span>`;
                },
            },
            {
                data: "status",
                render: function (data) {
                    return `<span class="whitespace-nowrap text-s text-gray-600">${data}</span>`;
                },
            },
            {
                data: "approval_status",
                render: function (data) {
                    return `<span class="whitespace-nowrap text-s text-gray-600">${data}</span>`;
                },
            },
            { data: "approved_by" },
            { data: "duration" },
            { data: "resolved_at" },
        ],

        initComplete: function () {
            $(this.api().table().container()).addClass(
                "datatable-custom-wrapper",
            );
        },

        order: [[1, "desc"]],
    });
});