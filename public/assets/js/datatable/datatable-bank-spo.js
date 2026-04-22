// public/assets/js/datatable-bank-spo.js
let table = $("#bankSpoTable").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: window.location.href,
        type: "GET",
        data: function (d) {
            d.periode_dari = $("input[name=periode_dari]").val();
            d.periode_sampai = $("input[name=periode_sampai]").val();
            d.unit = $("#unit").val();
            d.jenis_spo = $("#jenis_spo").val();

            console.log("UNIT:", d.unit);
        },
    },
    language: {
        zeroRecords: "Tidak ada data yang ditemukan",
        emptyTable: "Tidak ada data tersedia",
    },
    columns: [
        {
            data: "file_pdf",
            render: function (data) {
                return `
                        <div class="flex items-center gap-2">
                            <i class="fas fa-file-pdf text-red-500"></i>
                            ${data}
                        </div>
                    `;
            },
        },
        {
            data: "unit",
            render: function (data) {
                return `<span class="px-2 py-1 bg-indigo-100 rounded text-xs">${data}</span>`;
            },
        },
        {
            data: "jenis_spo",
            render: function (data) {
                let color =
                    data === "SPO Utama"
                        ? "bg-blue-100 text-blue-800"
                        : "bg-green-100 text-green-800";

                return `<span class="px-2 py-1 ${color} rounded text-xs">${data}</span>`;
            },
        },
        {
            data: "tanggal_formatted",
        },
        {
            data: null,
            orderable: false,
            searchable: false,
            render: function (data) {
                let btn = `<div class="flex items-center gap-2">`;

                if (data.can_update) {
                    btn += `
                    <a href="/komite-mutu/bank-spo/${data.id}/edit"
                    class="text-slate-500 hover:text-blue-600 transition">
                        <i class="fas fa-pen-to-square"></i>
                    </a>
                    `;
                }

                if (data.can_read) {
                    btn += `
                        <a href="/komite-mutu/bank-spo/${data.id}"
                        class="text-slate-500 hover:text-cyan-600 transition" style="margin: 2px;"
                        title="Lihat File">
                            <i class="fas fa-eye"></i>
                        </a>
                        `;
                }

                if (data.can_delete) {
                    btn += `
                            <form action="/komite-mutu/bank-spo/${data.id}" method="POST" style="display:inline;">
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
