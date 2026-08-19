{{-- DataTable --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
        <h6 class="mb-0 font-bold text-lg">Detail Tiket</h6>

        <span class="font-normal text-gray-500">
            ({{ request('periode_dari', now()->startOfMonth()->format('d-m-Y')) }}
            s/d
            {{ request('periode_sampai', now()->format('d-m-Y')) }})
        </span>
    </div>
    <div class="p-4 overflow-x-auto">
        <table id="ticketTable" class="datatable-custom w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">No. Tiket
                    </th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">
                        Tanggal/Jam
                    </th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Nama
                        Pelapor
                    </th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Divisi
                    </th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Kategori
                    </th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Tingkat
                        Urgensi
                    </th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600">Deskripsi</th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Status
                        Tiket</th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Status
                        Approval
                    </th>
                    </th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Approved
                        By</th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Estimasi
                    </th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Selesai
                    </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
