// public/assets/js/datatable-helpdesk.js
let table = $("#ticketTable").DataTable({
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
                return `<span class="font-semibold">${data}</span>`;
            },
        },
        {
            data: "user_name",
        },
        {
            data: "user_unit",
        },
        {
            data: "created_at_formatted",
            render: function (data, type, row) {
                if (type === "sort" || type === "type") {
                    return row.created_at_timestamp;
                }
                return data;
            },
        },
        {
            data: "category",
        },
        {
            data: "urgency_badge",
        },
        {
            data: "status_badge",
        },
        {
            data: "approval_badge",
        },
        {
            data: null,
            orderable: false,
            searchable: false,
            render: function (data) {
                return `
                    <div class="flex items-center justify-center gap-2">
                        <a href="/admin/helpdesk/${data.id}"
                        class="text-slate-500 hover:text-cyan-600 transition"
                        title="Lihat Data">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                `;
            },
        },
    ],

    initComplete: function () {
        $(this.api().table().container()).addClass("datatable-custom-wrapper");
    },

    order: [[3, "desc"]],
});
