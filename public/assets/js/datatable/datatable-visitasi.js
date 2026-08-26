// public/assets/js/datatable/datatable-visitasi.js
$(document).ready(function () {
    $("#visitasiTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: window.location.href,
            type: "GET",
            data: function (d) {
                d.periode_dari = $("input[name=periode_dari]").val();
                d.periode_sampai = $("input[name=periode_sampai]").val();
            },
        },
        responsive: true,
        pageLength: 10,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Semua"],
        ],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous",
            },
            zeroRecords: "No matching records found",
            emptyTable: "No data available in table",
        },
        columns: [
            { data: "nama" },
            {
                data: "tim",
                render: function (data) {
                    return `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-teal-100 text-teal-800">${data}</span>`;
                },
            },
            {
                data: "tanggal_formatted",
                render: function (data, type, row) {
                    if (type === "sort" || type === "type") {
                        return row.tanggal_timestamp;
                    }
                    return `<div class="flex flex-col">
                                <span>${data}</span>
                            </div>`;
                },
            },
            { data: "kendala" },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data) {
                    let btn = `<div class="flex items-center justify-center gap-2">`;

                    if (data.can_update) {
                        btn += `
                        <a href="/visitasi/${data.id}/edit"
                        class="text-slate-500 hover:text-emerald-600 transition" title="Edit">
                            <i class="fas fa-pen-to-square"></i>
                        </a>
                        `;
                    }

                    if (data.can_read) {
                        btn += `
                            <a href="/visitasi/${data.id}"
                            class="text-slate-500 hover:text-cyan-600 transition" style="margin: 2px;"
                            title="Lihat Data">
                                <i class="fas fa-eye"></i>
                            </a>
                            `;
                    }

                    if (data.can_delete) {
                        btn += `
                                <form action="/visitasi/${data.id}" method="POST" style="display:inline;">
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