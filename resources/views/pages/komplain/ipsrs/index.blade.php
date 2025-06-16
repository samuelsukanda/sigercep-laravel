@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h6 class="text-xl font-bold text-slate-700 dark:text-white">Daftar Komplain IPSRS</h6>
            <a href="{{ route('komplain.ipsrs.create') }}"
                class="text-slate-700 dark:text-white text-sm px-6 py-2 rounded-lg shadow-md border border-transparent dark:border-white hover:shadow-lg transition">
                Tambah Data
            </a>

        </div>

        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100 p-4 rounded-lg mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="relative overflow-x-auto shadow-md rounded-lg px-2 dark:text-white">
            <table id="datatable" class="min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
                <thead class="text-xs text-slate-500 uppercase bg-slate-100 dark:text-white">
                    <tr>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Unit</th>
                        <th class="px-6 py-3">Tujuan Unit</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Kendala</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-s text-slate-500 bg-slate-100 dark:text-white">
                    @foreach ($komplain as $item)
                        <tr>
                            <td class="px-6 py-4 text-slate-700 dark:text-white">{{ $item->nama }}</td>
                            <td class="px-6 py-4 text-slate-700 dark:text-white">{{ $item->unit }}</td>
                            <td class="px-6 py-4 text-slate-700 dark:text-white">{{ $item->tujuan_unit }}</td>
                            <td class="px-6 py-4 text-slate-700 dark:text-white">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4 text-slate-700 dark:text-white">{{ $item->kendala }}</td>
                            <td class="px-6 py-4 text-slate-700 dark:text-white">
                                @include('components.status-badge', ['status' => $item->status])
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                <a href="{{ route('komplain.ipsrs.edit', $item->id) }}"
                                    class="text-emerald-600 dark:text-blue-400 hover:underline text-sm">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('komplain.ipsrs.destroy', $item->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin hapus?')"
                                        class="text-red-600 dark:text-red-400 hover:underline text-sm">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection