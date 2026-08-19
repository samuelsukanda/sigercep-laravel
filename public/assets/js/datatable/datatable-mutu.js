// public/assets/js/datatable/datatable-mutu.js
let table = $("#mutuTable").DataTable({
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
    },
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
        { data: "indikator" },
        {
            data: "periode",
            render: function (data) {
                return `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">${data}</span>`;
            },
        },
        { data: "unit" },
        { data: "pj_data" },
        { data: "numerator" },
        { data: "penumerator" },
        {
            data: "capaian",
            render: function (data) {
                const val = parseFloat(data) || 0;
                const cls =
                    val >= 80
                        ? "bg-green-100 text-green-800"
                        : val >= 60
                            ? "bg-yellow-100 text-yellow-800"
                            : "bg-red-100 text-red-800";
                return `<span class="px-2 py-1 text-xs font-semibold rounded-full ${cls}">${data}%</span>`;
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
                    <a href="/komite-mutu/mutu/${data.id}/edit"
                    class="text-slate-500 hover:text-emerald-600 transition" title="Edit">
                        <i class="fas fa-pen-to-square"></i>
                    </a>
                    `;
                }

                if (data.can_read) {
                    btn += `
                        <a href="/komite-mutu/mutu/${data.id}"
                        class="text-slate-500 hover:text-cyan-600 transition" style="margin: 2px;"
                        title="Lihat Data">
                            <i class="fas fa-eye"></i>
                        </a>
                        `;
                }

                if (data.can_delete) {
                    btn += `
                            <form action="/komite-mutu/mutu/${data.id}" method="POST" style="display:inline;">
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
});