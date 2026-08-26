// public/assets/js/datatable/datatable-change-request.js
let table = $("#changeRequestTable").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: window.location.href,
        type: "GET",
        data: function (d) {
            d.periode_dari = $("input[name=periode_dari]").val();
            d.periode_sampai = $("input[name=periode_sampai]").val();
            d.status_dokumen = $("#filter_status_dokumen").val();
            d.status_pengerjaan = $("#filter_status_pengerjaan").val();
        },
    },
    language: {
        zeroRecords: "No matching records found",
        emptyTable: "No data available in table",
    },
    columns: [
        {
            // No / Nomor Urut (Auto Increment)
            data: null,
            sortable: false,
            orderable: false,
            searchable: false,
            render: function (data, type, row, meta) {
                return `<span class="font-semibold text-slate-700">${meta.row + 1 + meta.settings._iDisplayStart}</span>`;
            },
        },
        {
            // Tanggal Permintaan
            data: "tanggal_formatted",
            render: function (data, type, row) {
                if (type === "sort" || type === "type") {
                    return row.created_at_timestamp;
                }
                return data;
            },
        },
        {
            // Permintaan Fitur
            data: "permintaan_fitur",
            render: function (data) {
                return data || "-";
            },
        },
        {
            // Deskripsi
            data: "deskripsi",
            render: function (data) {
                const max = 80;
                const text = data.length > max ? data.substring(0, max) + "..." : data;
                return `<span title="${data.replace(/"/g, '&quot;')}">${text}</span>`;
            },
        },
        {
            // Status Dokumen
            data: "status_dokumen",
            render: function (data) {
                const colors = {
                    "Terpenuhi": "background-color:#b3e5fc; color:#01579b;",
                    "Dalam Proses": "background-color:#ffe0b2; color:#e65100;",
                    "Tidak Ada": "background-color:#ffcdd2; color:#b71c1c;",
                };
                const style = colors[data] || "background-color:#e0e0e0; color:#333;";
                return `<span class="px-3 py-1 text-xs font-semibold rounded-full" style="${style}">${data}</span>`;
            },
        },
        {
            // Status Pengerjaan
            data: "status_pengerjaan",
            render: function (data) {
                const colors = {
                    "Done": "background-color:#0b5394; color:#ffffff;",
                    "In Progress": "background-color:#fce5cd; color:#783f04;",
                    "Open": "background-color:#d9ead3; color:#274e13;",
                    "Closed": "background-color:#4c382b; color:#ffffff;",
                    "Pending": "background-color:#674ea7; color:#ffffff;",
                    "QC": "background-color:#134f5c; color:#ffffff;",
                };
                const style = colors[data] || "background-color:#95a5a6; color:#ffffff;";
                return `<span class="px-3 py-1 text-xs font-semibold rounded-full" style="${style}">${data}</span>`;
            },
        },
        {
            // No Tiket
            data: "no_tiket",
            render: function (data) {
                if (!data || data === "No Tiket") {
                    return `<span class="text-xs text-slate-400" style="font-style: italic !important;">#No Tiket</span>`;
                }
                return data.startsWith('#') ? data : `#${data}`;
            },
        },
        {
            // Status Approval
            data: "approval_1_status",
            render: function (data, type, row) {
                const s1 = row.approval_1_status || "Menunggu";
                const s2 = row.approval_2_status || "Menunggu";
                let text, style;
                if (s2 === "Disetujui") {
                    text = "Approved";
                    style = "background-color:#0f766e; color:#ffffff;";
                } else if (s1 === "Disetujui") {
                    text = "Approved by " + (row.approval_1_by || "-");
                    style = "background-color:#d1fae5; color:#065f46;";
                } else if (s1 === "Ditolak" || s2 === "Ditolak") {
                    text = "Ditolak";
                    style = "background-color:#fee2e2; color:#991b1b;";
                } else {
                    text = "Pending";
                    style = "background-color:#fef3c7; color:#92400e;";
                }
                return `<span class="px-2.5 py-0.5 text-xs font-semibold rounded-full" style="${style}">${text}</span>`;
            },
        },
        {
            // Aksi
            data: null,
            orderable: false,
            searchable: false,
            render: function (data) {
                let btn = `<div class="flex items-center justify-center gap-2">`;



                if (data.can_update) {
                    btn += `
                    <a href="/change-request/${data.id}/edit"
                        class="text-slate-500 hover:text-blue-600 transition"
                        title="Edit">
                        <i class="fas fa-pen-to-square"></i>
                    </a>
                    `;
                }

                if (data.can_read) {
                    btn += `
                    <a href="/change-request/${data.id}"
                        class="text-slate-500 hover:text-cyan-600 transition"
                        style="margin: 2px;"
                        title="Lihat Data">
                        <i class="fas fa-eye"></i>
                    </a>
                    `;
                }

                if (data.can_delete) {
                    btn += `
                    <form action="/change-request/${data.id}" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr("content")}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button"
                            class="delete-button text-red-500 hover:text-red-700 transition"
                            data-confirm="Yakin ingin menghapus Change Request ini?"
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

    order: [[1, "desc"]],
});
