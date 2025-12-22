@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h6 class="text-xl font-bold text-slate-700 dark:text-white">Daftar Pengajuan Dokumen</h6>

            @canAccess('pengajuan_dokumen', 'create')
            <x-button.link href="{{ route('komite-mutu.pengajuan-dokumen.create') }}">
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

        <div class="relative overflow-x-auto shadow-md rounded-lg px-2 dark:text-white">
            <table id="datatable" class="min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
                <thead class="text-xs text-slate-500 uppercase bg-slate-100 dark:text-white">
                    <tr>
                        <th class="px-6 py-3">Judul Dokumen</th>
                        <th class="px-6 py-3">Jenis Dokumen</th>
                        <th class="px-6 py-3">Nomor Dokumen</th>
                        <th class="px-6 py-3">Kategori Pengajuan</th>
                        <th class="px-6 py-3">Tanggal Pengajuan</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-s text-slate-500 bg-slate-100 dark:text-white">
                    @foreach ($pengajuanDokumen as $item)
                        <tr>
                            <td class="px-6 py-4">{{ $item->judul_dokumen }}</td>
                            <td class="px-6 py-4">{{ $item->jenis_dokumen }}</td>
                            <td class="px-6 py-4">{{ $item->nomor_dokumen }}</td>
                            <td class="px-6 py-4">{{ $item->kategori_pengajuan }}</td>
                            <td class="px-6 py-4"
                                data-order="{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->timestamp }}">
                                {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4 space-x-2 text-center">
                                @canAccess('pengajuan_dokumen', 'update')
                                <x-button.action href="{{ route('komite-mutu.pengajuan-dokumen.edit', $item->id) }}"
                                    icon="pen-to-square" color="emerald" title="Edit" />
                                @endcanAccess

                                @canAccess('pengajuan_dokumen', 'read')
                                <x-button.action href="{{ route('komite-mutu.pengajuan-dokumen.show', $item->id) }}"
                                    icon="eye" color="emerald" title="Lihat Data" />
                                @endcanAccess

                                @canAccess('pengajuan_dokumen', 'delete')
                                <x-button.action href="{{ route('komite-mutu.pengajuan-dokumen.destroy', $item->id) }}"
                                    icon="trash" color="red" type="button" method="DELETE" title="Hapus" />
                                @endcanAccess
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/alert-delete.js') }}"></script>

    <script>
        $(document).ready(function() {
            let table = $('#datatable').DataTable();
            table.order([4, 'desc']).draw();
        });
    </script>
@endpush
