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
                let isAdmin = window.location.pathname.startsWith('/admin');
                let viewUrl = isAdmin ? `/admin/helpdesk/${data.id}` : `/helpdesk/${data.id}`;
                let btn = `<div class="flex items-center justify-center gap-2">`;

                if (isAdmin && data.can_update) {
                    btn += `
                    <a href="/admin/helpdesk/${data.id}/edit"
                    class="text-slate-500 hover:text-blue-600 transition"
                    title="Edit">
                        <i class="fas fa-pen-to-square"></i>
                    </a>
                    `;
                }

                btn += `
                    <a href="${viewUrl}"
                    class="text-slate-500 hover:text-cyan-600 transition"
                    title="Lihat Data">
                        <i class="fas fa-eye"></i>
                    </a>
                `;

                if (isAdmin && data.can_delete) {
                    btn += `
                    <form action="/admin/helpdesk/${data.id}" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr("content")}">
                        <input type="hidden" name="_method" value="DELETE">

                        <button type="button"
                            class="delete-button text-red-500 hover:text-red-700 transition"
                            data-confirm="Yakin ingin menghapus tiket ini?"
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
