// public/assets/js/datatable/datatable-manajemen-risiko.js
$(document).ready(function () {
    function getBadgeColor(tingkat) {
        if (!tingkat) return "bg-slate-100 text-slate-500";
        const t = String(tingkat).toLowerCase().trim();
        if (t.includes("sangat rendah")) return "bg-green-100 text-green-700";
        if (t.includes("rendah")) return "bg-yellow-100 text-yellow-700";
        if (t.includes("sangat tinggi")) return "bg-red-200 text-red-800 font-bold";
        if (t.includes("tinggi")) return "bg-red-100 text-red-700";
        if (t.includes("sedang")) return "bg-orange-100 text-orange-700";
        return "bg-slate-100 text-slate-700";
    }

    function tingkatRender(data, type, row, nilai) {
        const badge = `<span class="px-2 py-1 text-xs font-semibold rounded-full ${getBadgeColor(data)}">${data || "-"}</span>`;
        const nilaiHtml = nilai
            ? `<span class="block text-[10px] mt-1 text-slate-400">Nilai: ${nilai}</span>`
            : "";
        return badge + nilaiHtml;
    }

    var table = $("#manajemenRisikoTable").DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: {
            url: window.location.href,
            type: "GET",
            data: function (d) {
                d.unit = $("select[name=unit]").val();
                d.tingkat = $("select[name=tingkat]").val();
                d.kode_risiko = $("select[name=kode_risiko]").val();
            },
        },
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
            {
                data: null,
                orderable: false,
                searchable: false,
                className: "font-medium text-center",
                render: function () {
                    return "";
                },
            },
            { data: "no_urut" },
            { data: "unit", className: "font-bold" },
            { data: "risiko" },
            {
                data: "kode_risiko",
                className: "text-xs font-medium text-slate-500 dark:text-slate-400",
            },
            { data: "sebab", className: "text-xs" },
            { data: "dampak", className: "text-xs" },
            {
                data: "pengendalian",
                className: "text-xs",
                render: function (data, type, row) {
                    let html = data || "";
                    if (row.efektif) {
                        html += `<span class="block mt-1 text-emerald-600 font-semibold"><i class="fas fa-check mr-1"></i> Efektif</span>`;
                    }
                    if (row.tidak_efektif) {
                        html += `<span class="block mt-1 text-red-500 font-semibold"><i class="fas fa-times mr-1"></i> Tidak Efektif</span>`;
                    }
                    return html;
                },
            },
            {
                data: "analisis_tingkat",
                className: "text-center",
                render: function (data, type, row) {
                    return tingkatRender(data, type, row, row.analisis_nilai);
                },
            },
            {
                data: "target_waktu",
                className: "text-center text-xs",
                render: function (data) {
                    return data || "-";
                },
            },
            {
                data: "mitigasi_tw1_tingkat",
                className: "text-center",
                render: function (data, type, row) {
                    return tingkatRender(data, type, row, row.mitigasi_tw1_nilai);
                },
            },
            {
                data: "mitigasi_tw2_tingkat",
                className: "text-center",
                render: function (data, type, row) {
                    return tingkatRender(data, type, row, row.mitigasi_tw2_nilai);
                },
            },
            {
                data: "mitigasi_tw3_tingkat",
                className: "text-center",
                render: function (data, type, row) {
                    return tingkatRender(data, type, row, row.mitigasi_tw3_nilai);
                },
            },
            {
                data: "mitigasi_tw4_tingkat",
                className: "text-center",
                render: function (data, type, row) {
                    return tingkatRender(data, type, row, row.mitigasi_tw4_nilai);
                },
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: "text-center whitespace-nowrap",
                render: function (data) {
                    let btn = `<div class="flex items-center justify-center gap-2">`;

                    if (data.can_update) {
                        btn += `
                        <a href="/komite-mutu/manajemen-risiko/${data.id}/edit"
                        class="text-slate-500 hover:text-emerald-600 transition" title="Edit">
                            <i class="fas fa-pen-to-square"></i>
                        </a>
                        `;
                    }

                    if (data.can_read) {
                        btn += `
                            <a href="/komite-mutu/manajemen-risiko/${data.id}"
                            class="text-slate-500 hover:text-cyan-600 transition" style="margin: 2px;"
                            title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            `;
                    }

                    if (data.can_delete) {
                        btn += `
                                <form action="/komite-mutu/manajemen-risiko/${data.id}" method="POST" style="display:inline;">
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
        .on("draw.dt", function () {
            let i = 1;

            table
                .cells(null, 0, { search: "applied", order: "applied" })
                .every(function (cell) {
                    this.data(i++);
                });
        })
        .draw();
});