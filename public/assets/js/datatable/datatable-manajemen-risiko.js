// public/assets/js/datatable-manajemen-risiko.js
$(document).ready(function () {
    var table = $("#manajemenRisikoTable").DataTable({
        scrollX: true,
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

        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0,
            },
            {
                visible: false,
                targets: 1,
            }
        ],

        initComplete: function () {
            $(this.api().table().container()).addClass(
                "datatable-custom-wrapper",
            );
        },

        order: [[1, "asc"]],
    });

    table
        .on("order.dt search.dt", function () {
            let i = 1;

            table
                .cells(null, 0, { search: "applied", order: "applied" })
                .every(function (cell) {
                    this.data(i++);
                });
        })
        .draw();
});
