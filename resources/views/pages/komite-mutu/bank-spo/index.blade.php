@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
            <h6 class="text-xl font-bold text-slate-700 dark:text-white">Daftar Bank SPO</h6>

            @canAccess('bank_spo', 'create')
            <x-button.link href="{{ route('komite-mutu.bank-spo.create') }}">
                Tambah Data
            </x-button.link>
            @endcanAccess
        </div>

        @if (session('success'))
            <div
                class="relative text-s w-full p-4 mb-4 text-white border border-blue-300 border-solid rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
            {{-- Filter Tanggal --}}
            <div class="flex items-center gap-4">
                <div>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                        class="border rounded px-3 py-2">
                </div>
                <div>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                        class="border rounded px-3 py-2">
                </div>
            </div>

            <div class="filter flex gap-2">
                {{-- Filter Unit --}}
                <select id="filter-unit"
                    class="rounded-lg border border-gray-300 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Unit</option>
                    @foreach (config('units.spo') as $unit)
                        <option value="{{ $unit }}">{{ $unit }}</option>
                    @endforeach
                </select>

                {{-- Filter Jenis SPO --}}
                <select id="filter-jenis"
                    class="rounded-lg border border-gray-300 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Jenis</option>
                    <option value="SPO Utama">SPO Utama</option>
                    <option value="SPO Terkait">SPO Terkait</option>
                </select>
            </div>
        </div>

        <div class="relative overflow-x-auto shadow-md rounded-lg px-2 dark:text-white">
            <table id="datatable" class="min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
                <thead class="text-xs text-slate-500 uppercase bg-slate-100 dark:text-white">
                    <tr>
                        <th class="px-6 py-3">Nama File</th>
                        <th class="px-6 py-3">Unit</th>
                        <th class="px-6 py-3">Jenis SPO</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.confirmDelete = function(id) {
            const form = document.getElementById('delete-form-' + id);
            const confirmMessage = "Yakin ingin menghapus?";

            toastr.clear();

            toastr.options = {
                "closeButton": false,
                "progressBar": true,
                "timeOut": 0,
                "extendedTimeOut": 0,
                "positionClass": "toast-top-center",
                "onclick": null,
                "tapToDismiss": false,
                "onShown": function() {
                    const toastEl = document.querySelector('.toast-warning');

                    if (toastEl && !toastEl.querySelector('.action-area')) {
                        const actionArea = document.createElement('div');
                        actionArea.className = 'mt-3 mb-3 d-flex justify-content-center gap-2 action-area';

                        actionArea.innerHTML = `
                            <button class="btn btn-sm btn-outline-light" id="confirmDeleteBtn" style="font-weight:bold; min-width:80px;">Ya, Hapus</button>
                            <button class="btn btn-sm text-white" id="cancelDeleteBtn" style="text-decoration:none; font-weight:bold;">Batal</button>
                        `;
                        toastEl.appendChild(actionArea);

                        document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
                            form.submit();
                        });

                        document.getElementById('cancelDeleteBtn').addEventListener('click', () => {
                            toastr.remove();
                        });
                    }
                }
            };

            toastr.warning(confirmMessage, "Konfirmasi Hapus");
        };

        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#datatable')) {
                $('#datatable').DataTable().destroy();
                $('#datatable tbody').empty();
            }

            let table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('komite-mutu.bank-spo.index') }}',
                    type: 'GET',
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.unit = $('#filter-unit').val();
                        d.jenis_spo = $('#filter-jenis').val();
                    }
                },
                columns: [{
                        data: 'file_pdf',
                        name: 'file_pdf'
                    },
                    {
                        data: 'unit',
                        name: 'unit'
                    },
                    {
                        data: 'jenis_spo',
                        name: 'jenis_spo'
                    },
                    {
                        data: 'tanggal_formatted',
                        name: 'created_at'
                    },
                    {
                        data: null,
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let buttons = '<div class="flex justify-center items-center">';

                            // Tombol Edit
                            if (row.can_update) {
                                buttons += `
                                    <a href="{{ url('komite-mutu/bank-spo') }}/${row.id}/edit" 
                                    class="text-slate-700 hover:text-slate-900 hover:opacity-80 transition-all" 
                                    title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>`;
                            }

                            if (row.can_read) {
                                buttons += `
                                <a href="{{ url('komite-mutu/bank-spo') }}/${row.id}" 
                                class="p-1 text-slate-700 hover:text-slate-900 hover:opacity-80 transition-all" 
                                title="Lihat Data">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>`;
                            }

                            if (row.can_delete) {
                                buttons += `
                                <button type="button" 
                                        onclick="window.confirmDelete(${row.id})"
                                        class="p-0.5 text-red-600 hover:text-red-700 hover:opacity-80 transition-all" 
                                        title="Hapus">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                                <form id="delete-form-${row.id}" 
                                        action="{{ url('komite-mutu/bank-spo') }}/${row.id}" 
                                        method="POST" style="display:none">
                                    @csrf
                                    @method('DELETE')
                                </form>`;
                            }

                            buttons += '</div>';
                            return buttons;
                        }
                    }
                ],
                order: [
                    [3, 'desc']
                ],
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                language: {
                    processing: "Memproses...",
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    loadingRecords: "Memuat...",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    emptyTable: "Tidak ada data",
                    paginate: {
                        first: "Pertama",
                        previous: "Sebelumnya",
                        next: "Selanjutnya",
                        last: "Terakhir"
                    }
                }
            });

            $('#start_date, #end_date, #filter-unit, #filter-jenis').on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
