@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h6 class="text-xl font-bold text-slate-700 dark:text-white">Daftar Pengembalian Aset</h6>

            @canAccess('pengembalian_aset', 'create')
            <x-button.link href="{{ route('pengadaan-aset.pengembalian-aset.create') }}">
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

        <div class="flex justify-end items-center mb-4 flex-wrap gap-2">
            {{-- Filter Tanggal --}}
            <form method="GET" action="{{ route('komplain.ipsrs.index') }}" class="flex items-center gap-4 mb-4">
                <div>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                        class="border rounded px-3 py-2 w-full">
                </div>
                <div>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                        class="border rounded px-3 py-2 w-full">
                </div>
            </form>
        </div>

        <div class="relative overflow-x-auto shadow-md rounded-lg px-2 dark:text-white">
            <table id="datatable" data-date-column="3"
                class="min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
                <thead class="text-xs text-slate-500 uppercase bg-slate-100 dark:text-white">
                    <tr>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Unit</th>
                        <th class="px-6 py-3">Keperluan</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Nama Barang</th>
                        <th class="px-6 py-3">Tempat Asal Barang</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-s text-slate-500 bg-slate-100 dark:text-white">
                    @foreach ($pengadaan as $item)
                        <tr>
                            <td class="px-6 py-4">{{ $item->nama }}</td>
                            <td class="px-6 py-4">{{ $item->unit }}</td>
                            <td class="px-6 py-4">{{ $item->keperluan }}</td>
                            <td class="px-6 py-4" data-order="{{ \Carbon\Carbon::parse($item->tanggal)->timestamp }}">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4">{{ $item->nama_barang }}</td>
                            <td class="px-6 py-4">{{ $item->tempat_asal_barang }}</td>
                            <td class="px-6 py-4 space-x-2 text-center">
                                @canAccess('pengembalian_aset', 'update')
                                <x-button.action href="{{ route('pengadaan-aset.pengembalian-aset.edit', $item->id) }}"
                                    icon="pen-to-square" color="emerald" title="Edit" />
                                @endcanAccess

                                @canAccess('pengembalian_aset', 'read')
                                <x-button.action href="{{ route('pengadaan-aset.pengembalian-aset.show', $item->id) }}"
                                    icon="eye" color="emerald" title="Lihat Data" />
                                @endcanAccess

                                @canAccess('pengembalian_aset', 'delete')
                                <x-button.action href="{{ route('pengadaan-aset.pengembalian-aset.destroy', $item->id) }}"
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
            table.order([3, 'desc']).draw();
        });
    </script>
@endpush
