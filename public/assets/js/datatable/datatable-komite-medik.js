// public/assets/js/datatable/datatable-komite-medik.js
$(document).ready(function () {
    $("#komiteMedikTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: window.location.href,
            type: "GET",
            data: function (d) {
                d.periode_dari = $("input[name=periode_dari]").val();
                d.periode_sampai = $("input[name=periode_sampai]").val();
                d.unit = $("#unit").val();
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
                data: "file_pdf",
                render: function (data) {
                    return `<div class="flex items-center gap-2">
                        <i class="fas fa-file-pdf text-red-500 text-lg"></i>
                        ${data}
                    </div>`;
                },
            },
            {
                data: "unit",
                render: function (data) {
                    return `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">${data}</span>`;
                },
            },
            {
                data: "created_at_formatted",
                render: function (data, type, row) {
                    if (type === "sort" || type === "type") {
                        return row.created_at_timestamp;
                    }
                    return `<div class="flex flex-col">
                        <span>${data}</span>
                        <span class="text-xs text-gray-400">${row.created_at_time} WIB</span>
                    </div>`;
                },
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data) {
                    let btn = `<div class="flex items-center justify-center gap-2">`;

                    if (data.can_update) {
                        btn += `
                        <a href="/komite-medik/${data.id}/edit"
                        class="text-slate-500 hover:text-emerald-600 transition" title="Edit">
                            <i class="fas fa-pen-to-square"></i>
                        </a>
                        `;
                    }

                    if (data.can_read) {
                        btn += `
                            <a href="/komite-medik/${data.id}"
                            class="text-slate-500 hover:text-cyan-600 transition" style="margin: 2px;"
                            title="Lihat Data">
                                <i class="fas fa-eye"></i>
                            </a>
                            `;
                    }

                    if (data.can_delete) {
                        btn += `
                                <form action="/komite-medik/${data.id}" method="POST" style="display:inline;">
                                    <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr("content")}">
                                    <input type="hidden" name="_method" value="DELETE">

                                    <button type="button"
                                        class="delete-button text-red-500 hover:text-red-700 transition"
                                        data-confirm="Yakin ingin menghapus data ini?"
                                        title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                `;
                    }

                    btn += `</div>`;
                    return btn;
                },
            },
        ],

        initComplete: function () {
            $(this.api().table().container()).addClass(
                "datatable-custom-wrapper",
            );
        },

        order: [[2, "desc"]],
    });
});