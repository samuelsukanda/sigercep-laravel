@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
            <h6 class="text-xl font-bold text-slate-700 dark:text-white">Daftar UTW</h6>
            <x-button.link href="{{ route('sdm-hukum.utw.create') }}">
                Tambah Data
            </x-button.link>
        </div>

        <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
            {{-- Filter Tanggal --}}
            <form method="GET" action="{{ route('sdm-hukum.utw.index') }}" class="flex items-center gap-4 mb-4">
                <div>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                        class="border rounded px-3 py-2 w-full">
                </div>
                <div>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                        class="border rounded px-3 py-2 w-full">
                </div>
            </form>

            <div class="filter">
                {{-- Filter Unit --}}
                <select id="filter-unit"
                    class="rounded-lg border border-gray-300 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Unit</option>
                    @foreach (config('units.utw') as $unit)
                        <option value="{{ $unit }}">{{ $unit }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if (session('success'))
            <div
                class="relative text-s w-full p-4 mb-4 text-white border border-blue-300 border-solid rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500">
                {{ session('success') }}
            </div>
        @endif

        <div class="relative overflow-x-auto shadow-md rounded-lg px-2 dark:text-white">
            <table id="datatable" data-date-column="3"
                class="min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
                <thead class="text-xs text-slate-500 uppercase bg-slate-100 dark:text-white">
                    <tr>
                        <th class="px-6 py-3">Nama File</th>
                        <th class="px-6 py-3">Unit</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-s text-slate-500 bg-slate-100 dark:text-white">
                    @foreach ($utw as $item)
                        <tr>
                            <td class="px-6 py-4">{{ $item->nama_file }}</td>
                            <td class="px-6 py-4">{{ $item->unit }}</td>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y H:i') }}
                            </td>
                            <td class="px-6 py-4 space-x-2 text-center">
                                <x-button.action href="{{ route('sdm-hukum.utw.edit', $item->id) }}" icon="pen-to-square"
                                    color="emerald" title="Edit" />
                                <x-button.action href="{{ route('sdm-hukum.utw.show', $item->id) }}" icon="eye"
                                    color="emerald" title="Lihat Data" />
                                <x-button.action href="{{ route('sdm-hukum.utw.destroy', $item->id) }}" icon="trash"
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
