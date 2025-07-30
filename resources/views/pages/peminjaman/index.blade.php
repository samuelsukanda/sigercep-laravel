@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h6 class="text-xl font-bold text-slate-700 dark:text-white">Daftar Peminjaman</h6>
            <x-button.link href="{{ route('peminjaman.create') }}">
                Tambah Data
            </x-button.link>
        </div>

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

        @if (session('success'))
            <div
                class="relative text-s w-full p-4 mb-4 text-white border border-blue-300 border-solid rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500">
                {{ session('success') }}
            </div>
        @endif

        <div class="relative overflow-x-auto shadow-md rounded-lg px-2 dark:text-white">
            <table id="datatable" data-date-column="2" class="min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
                <thead class="text-xs text-slate-500 uppercase bg-slate-100 dark:text-white">
                    <tr>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Unit</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Alat/Barang</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-s text-slate-500 bg-slate-100 dark:text-white">
                    @foreach ($peminjaman as $item)
                        <tr>
                            <td class="px-6 py-4">{{ $item->nama }}</td>
                            <td class="px-6 py-4">{{ $item->unit }}</td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4">{{ $item->barang }}</td>
                            <td class="px-6 py-4 space-x-2 text-center">
                                <x-button.action href="{{ route('peminjaman.edit', $item->id) }}" icon="pen-to-square"
                                    color="emerald" title="Edit" />
                                <x-button.action href="{{ route('peminjaman.show', $item->id) }}" icon="eye"
                                    color="emerald" title="Lihat Data" />
                                <x-button.action href="{{ route('peminjaman.destroy', $item->id) }}" icon="trash"
                                    color="red" type="button" method="DELETE" title="Hapus" />
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
@endpush
