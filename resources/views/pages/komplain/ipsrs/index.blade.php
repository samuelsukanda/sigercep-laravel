@extends('layouts.app')

@section('title', 'SIGERCEP')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h6 class="text-xl font-bold text-slate-700">Daftar Komplain IPSRS</h6>
            <a href="{{ route('komplain.ipsrs.create') }}"
                class="bg-gradient-to-tr from-blue-600 to-cyan-400 text-slate-700 text-sm px-6 py-2 rounded-lg shadow-md">Tambah
                Data</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="relative overflow-x-auto shadow-md rounded-lg">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-500 uppercase bg-slate-100">
                    <tr>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Unit</th>
                        <th class="px-6 py-3">Tujuan</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($komplain as $item)
                        <tr class="bg-white border-b hover:bg-slate-50">
                            <td class="px-6 py-4">{{ $item->nama }}</td>
                            <td class="px-6 py-4">{{ $item->unit }}</td>
                            <td class="px-6 py-4">{{ $item->tujuan_unit }}</td>
                            <td class="px-6 py-4">{{ $item->tanggal }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 text-xs rounded-full {{ $item->status === 'Done'
                                        ? 'bg-green-100 text-green-700'
                                        : ($item->status === 'On Progress'
                                            ? 'bg-yellow-100 text-yellow-700'
                                            : 'bg-red-100 text-red-700') }}">
                                    {{ $item->status ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <a href="{{ route('komplain.ipsrs.edit', $item->id) }}"
                                    class="text-blue-600 hover:underline text-sm">Edit</a>
                                <form action="{{ route('komplain.ipsrs.destroy', $item->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin hapus?')"
                                        class="text-red-600 hover:underline text-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
