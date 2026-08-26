// public/assets/js/datatable/datatable-pengembalian-aset.js
let table = $("#pengembalianAsetTable").DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    pageLength: 10,
    lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "Semua"],
    ],
    ajax: {
        url: window.location.href,
        type: "GET",
        data: function (d) {
            d.periode_dari = $("input[name=periode_dari]").val();
            d.periode_sampai = $("input[name=periode_sampai]").val();
        },
    },
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
        {
            data: "nama",
            render: function (data) {
                return `<span class="font-medium">${data}</span>`;
            },
        },
        { data: "unit" },
        { data: "keperluan" },
        {
            data: "tanggal_formatted",
            render: function (data, type, row) {
                if (type === "sort" || type === "type") {
                    return row.tanggal_timestamp;
                }
                return data;
            },
        },
        {
            data: "nama_barang",
            render: function (data) {
                return `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">${data}</span>`;
            },
        },
        { data: "tempat_asal_barang" },
        {
            data: null,
            orderable: false,
            searchable: false,
            render: function (data) {
                let btn = `<div class="flex items-center justify-center gap-2">`;

                if (data.can_update) {
                    btn += `
                    <a href="/pengadaan-aset/pengembalian-aset/${data.id}/edit"
                    class="text-slate-500 hover:text-emerald-600 transition" title="Edit">
                        <i class="fas fa-pen-to-square"></i>
                    </a>
                    `;
                }

                if (data.can_read) {
                    btn += `
                        <a href="/pengadaan-aset/pengembalian-aset/${data.id}"
                        class="text-slate-500 hover:text-cyan-600 transition" style="margin: 2px;"
                        title="Lihat Data">
                            <i class="fas fa-eye"></i>
                        </a>
                        `;
                }

                if (data.can_delete) {
                    btn += `
                            <form action="/pengadaan-aset/pengembalian-aset/${data.id}" method="POST" style="display:inline;">
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
        $(this.api().table().container()).addClass("datatable-custom-wrapper");
    },

    order: [[3, "desc"]],
});