@if (\App\Helpers\PermissionHelper::canAccess('pelaporan_ikp', 'update') && \App\Helpers\PermissionHelper::canAccess('pelaporan_ikp', 'delete'))
    <div class="relative overflow-x-auto shadow-md rounded-lg px-2 bg-white dark:text-white">
        <table id="pelaporanIkpTable" class="datatable-custom min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
            <thead class="text-xs text-slate-500 uppercase bg-white dark:text-white">
                <tr>
                    <th class="px-6 py-3">Nama Pasien</th>
                    <th class="px-6 py-3">No. Rekam Medis</th>
                    <th class="px-6 py-3">Tanggal Kejadian</th>
                    <th class="px-6 py-3">Jenis Kelamin</th>
                    <th class="px-6 py-3">Kelompok Umur</th>
                    <th class="px-6 py-3">Jenis Insiden</th>
                    <th class="px-6 py-3">Grading Risiko</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-s text-slate-500 bg-white">
                </tbody>
        </table>
    </div>
@else
    <div class="relative overflow-x-auto shadow-md rounded-lg px-2 bg-white dark:text-white">
        <div class="flex items-center justify-center min-h-[220px] text-center">
            <p class="text-sm text-gray-600">Anda tidak memiliki akses untuk melihat data pelaporan IKP.</p>
        </div>
    </div>
@endif
