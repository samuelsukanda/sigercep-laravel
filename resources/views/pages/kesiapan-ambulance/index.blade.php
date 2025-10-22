@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h6 class="text-xl font-bold text-slate-700 dark:text-white">Daftar Kesiapan Ambulance</h6>

            @canAccess('kesiapan_ambulance', 'create')
            <x-button.link href="{{ route('kesiapan-ambulance.create') }}">
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
                        <th class="px-6 py-3">Mobil Ambulance</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Perawat</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-s text-slate-500 bg-slate-100 dark:text-white">
                    @foreach ($ambulance as $item)
                        <tr>
                            <td class="px-6 py-4">{{ $item->mobil_ambulance }}</td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4">{{ $item->perawat }}</td>
                            <td class="px-6 py-4 space-x-2 text-center">
                                @canAccess('kesiapan_ambulance', 'update')
                                <x-button.action href="{{ route('kesiapan-ambulance.edit', $item->id) }}"
                                    icon="pen-to-square" color="emerald" title="Edit" />
                                @endcanAccess

                                @canAccess('kesiapan_ambulance', 'read')
                                <x-button.action href="{{ route('kesiapan-ambulance.show', $item->id) }}" icon="eye"
                                    color="emerald" title="Lihat Data" />
                                @endcanAccess

                                @canAccess('kesiapan_ambulance', 'delete')
                                <x-button.action href="{{ route('kesiapan-ambulance.destroy', $item->id) }}" icon="trash"
                                    color="red" type="button" method="DELETE" title="Hapus" />
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
@endpush
